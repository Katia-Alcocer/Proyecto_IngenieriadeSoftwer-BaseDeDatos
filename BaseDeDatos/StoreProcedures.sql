USE ProyectoHerreriaUG;
GO



CREATE PROCEDURE RegistrarCliente
    @Calle NVARCHAR(50),
    @Numero INT,
    @c_CP INT,

    @Nombre NVARCHAR(50),
    @Paterno NVARCHAR(50),
    @Materno NVARCHAR(50),
    @Telefono NVARCHAR(10),
    @Email NVARCHAR(50),
    @Edad SMALLINT,
    @Sexo CHAR(1),  -- 'H' o 'M'

    @Credito DECIMAL(10,2),
    @Limite DECIMAL(10,2),
    @idDescuento INT
AS
BEGIN
    SET NOCOUNT ON;

    BEGIN TRY
        -- Validar existencia previa de persona por tel�fono o email (sin considerar espacios ni may�sculas)
        IF EXISTS (
            SELECT 1 FROM Personas
            WHERE LTRIM(RTRIM(Telefono)) = LTRIM(RTRIM(@Telefono))
               OR LOWER(LTRIM(RTRIM(Email))) = LOWER(LTRIM(RTRIM(@Email)))
        )
        BEGIN
            THROW 50001, 'Ya existe una persona con ese tel�fono o email.', 1;
        END

        BEGIN TRANSACTION;

            DECLARE @idDomicilio INT;
            DECLARE @idPersona INT;
            DECLARE @idCliente INT;

            -- Insertar domicilio
            INSERT INTO Domicilios (Calle, Numero, c_CP)
            VALUES (@Calle, @Numero, @c_CP);

            SET @idDomicilio = SCOPE_IDENTITY();

            -- Insertar persona
            INSERT INTO Personas (Nombre, Paterno, Materno, Telefono, Email, Edad, Sexo, idDomicilio)
            VALUES (@Nombre, @Paterno, @Materno, @Telefono, @Email, @Edad, @Sexo, @idDomicilio);

            SET @idPersona = SCOPE_IDENTITY();

            -- Insertar cliente
            INSERT INTO Clientes (Credito, Limite, idPersona, idDescuento)
            VALUES (@Credito, @Limite, @idPersona, @idDescuento);

            SET @idCliente = SCOPE_IDENTITY();

        COMMIT TRANSACTION;
    END TRY
    BEGIN CATCH
        IF XACT_STATE() <> 0
            ROLLBACK TRANSACTION;

        DECLARE @ErrorMessage NVARCHAR(4000) = ERROR_MESSAGE();
        DECLARE @ErrorSeverity INT = ERROR_SEVERITY();
        DECLARE @ErrorState INT = ERROR_STATE();

        RAISERROR (@ErrorMessage, @ErrorSeverity, @ErrorState);
    END CATCH
END
GO





CREATE PROCEDURE RegistrarEmpleado
    @Nombre NVARCHAR(50),
    @Paterno NVARCHAR(50),
    @Materno NVARCHAR(50),
    @Telefono NVARCHAR(10),
    @Email NVARCHAR(50),
    @Edad SMALLINT,
    @Sexo CHAR(1), -- 'H' o 'M'
    @Calle NVARCHAR(50),
    @Numero INT,
    @c_CP INT,
    @RFC NVARCHAR(13),
    @CURP NVARCHAR(18),
    @NumeroSeguro NVARCHAR(11),
    @Usuario NVARCHAR(50),
    @Contrasena NVARCHAR(50)
AS
BEGIN
    SET NOCOUNT ON;

    BEGIN TRY
        -- Validaci�n previa: Tel�fono o Email ya existen
        IF EXISTS (
            SELECT 1 FROM Personas WHERE Telefono = @Telefono OR Email = @Email
        )
        BEGIN
            THROW 50001, 'Tel�fono o Email ya registrados', 1;
        END

        -- Validaci�n previa: Usuario ya existe
        IF EXISTS (
            SELECT 1 FROM Empleados WHERE Usuario = @Usuario
        )
        BEGIN
            THROW 50002, 'Usuario ya registrado', 1;
        END

        BEGIN TRANSACTION;

            DECLARE @idDomicilio INT;
            DECLARE @idPersona INT;
            DECLARE @idEmpleado INT;

            -- Insertar domicilio
            INSERT INTO Domicilios (Calle, Numero, c_CP)
            VALUES (@Calle, @Numero, @c_CP);

            SET @idDomicilio = SCOPE_IDENTITY();

            -- Insertar persona
            INSERT INTO Personas (Nombre, Paterno, Materno, Telefono, Email, Edad, Sexo, idDomicilio)
            VALUES (@Nombre, @Paterno, @Materno, @Telefono, @Email, @Edad, @Sexo, @idDomicilio);

            SET @idPersona = SCOPE_IDENTITY();

            -- Insertar empleado
            INSERT INTO Empleados (Puesto, RFC, NumeroSeguroSocial, Usuario, Contrase�a, idPersona)
            VALUES ('Agente de Venta', @RFC, @NumeroSeguro, @Usuario, @Contrasena, @idPersona);

            SET @idEmpleado = SCOPE_IDENTITY();

        COMMIT TRANSACTION;
    END TRY
    BEGIN CATCH
        IF XACT_STATE() <> 0
            ROLLBACK TRANSACTION;

        DECLARE @ErrorMessage NVARCHAR(4000) = ERROR_MESSAGE();
        DECLARE @ErrorSeverity INT = ERROR_SEVERITY();
        DECLARE @ErrorState INT = ERROR_STATE();

        RAISERROR (@ErrorMessage, @ErrorSeverity, @ErrorState);
    END CATCH
END
GO





ALTER TABLE Empleados
ADD CURP VARCHAR(18) NULL;
GO
ALTER TABLE Empleados
ADD CONSTRAINT UQ_Empleados_CURP UNIQUE (CURP);
GO



CREATE PROCEDURE InsertarDomicilioPorID
    @p_Calle VARCHAR(50),
    @p_Numero INT,
    @p_c_CP INT
AS
BEGIN
    SET NOCOUNT ON;

    -- Verificar si el c_CP existe en la tabla CodigosPostales
    IF EXISTS (
        SELECT 1 FROM CodigosPostales WHERE c_CP = @p_c_CP
    )
    BEGIN
        -- Insertar en la tabla Domicilios
        INSERT INTO Domicilios (Calle, Numero, c_CP)
        VALUES (@p_Calle, @p_Numero, @p_c_CP);
    END
    ELSE
    BEGIN
        -- Lanzar error personalizado
        THROW 50001, 'El c_CP proporcionado no existe.', 1;
    END
END;
GO




CREATE PROCEDURE sp_ObtenerCarritoPorEmpleado
    @empleadoId INT
AS
BEGIN
    SELECT 
        tv.idProducto,
        p.Nombre,
        tv.Cantidad,
        p.PrecioVenta,
        (p.PrecioVenta * tv.Cantidad) AS Total
    FROM Temp_Ventas tv
    INNER JOIN Productos p ON tv.idProducto = p.idProducto
    WHERE tv.idEmpleado = @empleadoId;
END;
GO



CREATE PROCEDURE DevolverVentaCompleta
    @p_idVenta INT
AS
BEGIN
    SET NOCOUNT ON;

    BEGIN TRANSACTION;

    BEGIN TRY
        -- Actualizar stock sumando las cantidades de todos los productos en el detalle de la venta
        UPDATE p
        SET p.stock = p.stock + dv.Cantidad
        FROM Productos p
        INNER JOIN DetalleVenta dv ON p.idProducto = dv.idProducto
        WHERE dv.idVenta = @p_idVenta;

        -- Eliminar todos los detalles de la venta
        DELETE FROM DetalleVenta WHERE idVenta = @p_idVenta;

        -- Marcar la venta como devuelta
        UPDATE Ventas
        SET Estatus = 'Devuelta'
        WHERE idVenta = @p_idVenta;

        COMMIT TRANSACTION;
    END TRY
    BEGIN CATCH
        ROLLBACK TRANSACTION;

        DECLARE @ErrorMessage NVARCHAR(4000), @ErrorSeverity INT, @ErrorState INT;
        SELECT 
            @ErrorMessage = ERROR_MESSAGE(),
            @ErrorSeverity = ERROR_SEVERITY(),
            @ErrorState = ERROR_STATE();

        RAISERROR (@ErrorMessage, @ErrorSeverity, @ErrorState);
    END CATCH
END;



CREATE PROCEDURE DevolverProductoIndividual
    @p_idVenta INT,
    @p_idProducto INT
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @v_cantidad INT;

    BEGIN TRANSACTION;

    BEGIN TRY
        -- Obtener la cantidad del producto en el detalle de venta
        SELECT @v_cantidad = Cantidad
        FROM DetalleVenta
        WHERE idVenta = @p_idVenta AND idProducto = @p_idProducto;

        IF @v_cantidad IS NULL
        BEGIN
            RAISERROR('Producto no encontrado en el detalle de la venta.', 16, 1);
            ROLLBACK TRANSACTION;
            RETURN;
        END

        -- Actualizar el stock del producto sumando la cantidad devuelta
        UPDATE Productos
        SET stock = stock + @v_cantidad
        WHERE idProducto = @p_idProducto;

        -- Eliminar ese producto del detalle de la venta
        DELETE FROM DetalleVenta
        WHERE idVenta = @p_idVenta AND idProducto = @p_idProducto;

        COMMIT TRANSACTION;
    END TRY
    BEGIN CATCH
        ROLLBACK TRANSACTION;

        DECLARE @ErrorMessage NVARCHAR(4000), @ErrorSeverity INT, @ErrorState INT;
        SELECT 
            @ErrorMessage = ERROR_MESSAGE(),
            @ErrorSeverity = ERROR_SEVERITY(),
            @ErrorState = ERROR_STATE();

        RAISERROR (@ErrorMessage, @ErrorSeverity, @ErrorState);
    END CATCH
END;



CREATE PROCEDURE ProcesarUnaVenta
    @p_id_empleado INT,
    @p_id_cliente INT,
    @p_tipo_pago VARCHAR(20), -- No hay ENUM en SQL Server, usar VARCHAR con validaci�n opcional
    @p_pago DECIMAL(10,2)
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE 
        @v_subtotal DECIMAL(10,2),
        @v_iva DECIMAL(10,2),
        @v_ieps DECIMAL(10,2),
        @v_monto DECIMAL(10,2),
        @v_cantidad_productos INT,
        @v_id_venta INT,
        @v_cliente INT,
        @v_credito_cliente DECIMAL(10,2),
        @v_limite_cliente DECIMAL(10,2);

    -- Asignar cliente: si no es v�lido, asignar 0 (cliente gen�rico)
    SET @v_cliente = CASE WHEN @p_id_cliente IS NULL OR @p_id_cliente <= 0 THEN 0 ELSE @p_id_cliente END;

    -- Calcular subtotal, IVA, IEPS y cantidad de productos sumados
    SELECT 
        @v_subtotal = SUM(p.PrecioVenta * t.Cantidad),
        @v_iva = SUM(p.PrecioVenta * t.Cantidad * 0.16), -- IVA 16%
        @v_ieps = SUM(p.PrecioVenta * t.Cantidad * 0.08), -- IEPS 8%
        @v_cantidad_productos = SUM(t.Cantidad)
    FROM Temp_Ventas t
    INNER JOIN Productos p ON t.idProducto = p.idProducto
    WHERE t.idEmpleado = @p_id_empleado;

    SET @v_monto = ISNULL(@v_subtotal,0) + ISNULL(@v_iva,0) + ISNULL(@v_ieps,0);

    -- Validar que el carrito no est� vac�o
    IF @v_cantidad_productos IS NULL OR @v_cantidad_productos = 0
    BEGIN
        RAISERROR('No hay productos en el carrito para procesar la venta.', 16, 1);
        RETURN;
    END

    -- Si es venta a credito, validar que cliente tenga suficiente credito
    IF @p_tipo_pago = 'Credito'
    BEGIN
        SELECT 
            @v_credito_cliente = Credito, 
            @v_limite_cliente = Limite
        FROM Clientes
        WHERE idCliente = @v_cliente;

        IF @v_cliente = 0
        BEGIN
            RAISERROR('Cliente gen�rico no puede comprar a cr�dito.', 16, 1);
            RETURN;
        END

        IF @v_credito_cliente < @v_monto
        BEGIN
            RAISERROR('Cr�dito insuficiente para realizar la venta.', 16, 1);
            RETURN;
        END

        -- Descontar el credito del cliente
        UPDATE Clientes
        SET Credito = Credito - @v_monto
        WHERE idCliente = @v_cliente;
    END

    BEGIN TRANSACTION;

    BEGIN TRY
        -- Insertar la venta
        INSERT INTO Ventas (Monto, Fecha, Subtotal, IVA, IEPS, CantidadProductos, TipoPago, Estatus, idCliente, idEmpleado)
        VALUES (
            @v_monto, 
            GETDATE(), 
            @v_subtotal, 
            @v_iva, 
            @v_ieps, 
            @v_cantidad_productos, 
            @p_tipo_pago, 
            CASE WHEN @p_tipo_pago = 'Credito' THEN 'En Espera de Pago' ELSE 'Pagada' END, 
            @v_cliente, 
            @p_id_empleado
        );

        SET @v_id_venta = SCOPE_IDENTITY();

        -- Insertar el detalle de la venta
        INSERT INTO DetalleVenta (Cantidad, Total, IVA, IEPS, idVenta, idProducto, idDescuento)
        SELECT 
            t.Cantidad,
            p.PrecioVenta * t.Cantidad,
            p.PrecioVenta * t.Cantidad * 0.16, -- IVA
            p.PrecioVenta * t.Cantidad * 0.08, -- IEPS
            @v_id_venta,
            t.idProducto,
            NULL -- Aqu� puedes a�adir l�gica para descuentos si quieres
        FROM Temp_Ventas t
        INNER JOIN Productos p ON t.idProducto = p.idProducto
        WHERE t.idEmpleado = @p_id_empleado;

        -- Actualizar stock de productos
        UPDATE p
        SET p.Stock = p.Stock - t.Cantidad
        FROM Productos p
        INNER JOIN Temp_Ventas t ON p.idProducto = t.idProducto
        WHERE t.idEmpleado = @p_id_empleado;

        -- Limpiar carrito
        DELETE FROM Temp_Ventas WHERE idEmpleado = @p_id_empleado;

        -- Insertar registro en Finanzas (invertido = 0 para simplificar, cambia seg�n necesites)
        INSERT INTO Finanzas (idVenta, TotalVenta, Invertido)
        VALUES (@v_id_venta, @v_monto, 0);

        COMMIT TRANSACTION;
    END TRY
    BEGIN CATCH
        ROLLBACK TRANSACTION;

        DECLARE @ErrorMessage NVARCHAR(4000), @ErrorSeverity INT, @ErrorState INT;
        SELECT 
            @ErrorMessage = ERROR_MESSAGE(),
            @ErrorSeverity = ERROR_SEVERITY(),
            @ErrorState = ERROR_STATE();

        RAISERROR (@ErrorMessage, @ErrorSeverity, @ErrorState);
    END CATCH
END;



CREATE PROCEDURE RestarCantidadProductoCarrito
    @p_id_empleado INT,
    @p_id_producto INT
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @v_cantidad INT;

    SELECT @v_cantidad = Cantidad 
    FROM Temp_Ventas 
    WHERE idEmpleado = @p_id_empleado AND idProducto = @p_id_producto;

    IF @v_cantidad > 1
    BEGIN
        UPDATE Temp_Ventas
        SET Cantidad = Cantidad - 1
        WHERE idEmpleado = @p_id_empleado AND idProducto = @p_id_producto;
    END
    ELSE IF @v_cantidad = 1
    BEGIN
        DELETE FROM Temp_Ventas
        WHERE idEmpleado = @p_id_empleado AND idProducto = @p_id_producto;
    END
    -- Si no hay registro, no hace nada
END;



CREATE PROCEDURE SumarCantidadProductoCarrito
    @p_id_empleado INT,
    @p_id_producto INT
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @v_stock INT;
    DECLARE @v_cantidad INT;

    SELECT @v_stock = Stock FROM Productos WHERE idProducto = @p_id_producto;
    SELECT @v_cantidad = Cantidad FROM Temp_Ventas WHERE idEmpleado = @p_id_empleado AND idProducto = @p_id_producto;

    IF @v_cantidad IS NOT NULL AND @v_cantidad < @v_stock
    BEGIN
        UPDATE Temp_Ventas
        SET Cantidad = Cantidad + 1
        WHERE idEmpleado = @p_id_empleado AND idProducto = @p_id_producto;
    END
END;




CREATE PROCEDURE AgregarAlCarrito
    @p_id_empleado INT,
    @p_id_producto INT,
    @p_cantidad INT
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @v_stock INT = 0;
    DECLARE @v_cantidad_actual INT = 0;
    DECLARE @v_cantidad_total INT = 0;

    -- Obtener el stock actual del producto
    SELECT @v_stock = Stock FROM Productos WHERE idProducto = @p_id_producto;

    -- Obtener la cantidad actual en el carrito para ese producto y empleado (si existe)
    SELECT @v_cantidad_actual = Cantidad 
    FROM Temp_Ventas 
    WHERE idEmpleado = @p_id_empleado AND idProducto = @p_id_producto;

    IF @v_cantidad_actual IS NULL
        SET @v_cantidad_actual = 0;

    SET @v_cantidad_total = @v_cantidad_actual + @p_cantidad;

    -- Validar si la cantidad total no supera el stock
    IF @v_cantidad_total <= @v_stock
    BEGIN
        -- Usar MERGE para insertar o actualizar
        MERGE Temp_Ventas AS target
        USING (SELECT @p_id_empleado AS idEmpleado, @p_id_producto AS idProducto) AS source
        ON (target.idEmpleado = source.idEmpleado AND target.idProducto = source.idProducto)
        WHEN MATCHED THEN 
            UPDATE SET Cantidad = Cantidad + @p_cantidad
        WHEN NOT MATCHED THEN
            INSERT (idEmpleado, idProducto, Cantidad)
            VALUES (@p_id_empleado, @p_id_producto, @p_cantidad);
    END
    ELSE
    BEGIN
        -- Lanzar error si se excede el stock
        THROW 50000, 'Cantidad solicitada excede el stock disponible.', 1;
    END
END;



CREATE PROCEDURE CambiarPedidoACancelado
    @p_id_pedido INT
AS
BEGIN
    UPDATE Pedidos
    SET Estatus = 'Cancelado'
    WHERE idPedido = @p_id_pedido;
END;



CREATE PROCEDURE CambiarPedidoAEnviado
    @p_id_pedido INT
AS
BEGIN
    UPDATE Pedidos
    SET Estatus = 'Enviado'
    WHERE idPedido = @p_id_pedido;
END;


CREATE PROCEDURE CambiarPedidoAAceptado
    @p_id_pedido INT
AS
BEGIN
    UPDATE Pedidos
    SET Estatus = 'Aceptado'
    WHERE idPedido = @p_id_pedido;
END;




CREATE PROCEDURE RegistrarPedido
    @p_id_cliente INT,
    @p_id_empleado INT,
    @p_fecha DATE
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @v_id_pedido INT;

    BEGIN TRY
        BEGIN TRANSACTION;

        -- Insertar pedido
        INSERT INTO Pedidos (idCliente, idEmpleado, Fecha, Estatus)
        VALUES (@p_id_cliente, @p_id_empleado, @p_fecha, 'Pendiente');

        SET @v_id_pedido = SCOPE_IDENTITY();

        -- Insertar detalles usando Temp_Ventas y precio actual de Productos
        INSERT INTO DetallePedidos (idPedido, idProducto, Cantidad, PrecioUnitario)
        SELECT
            @v_id_pedido,
            tv.idProducto,
            tv.Cantidad,
            p.PrecioVenta
        FROM Temp_Ventas tv
        INNER JOIN Productos p ON tv.idProducto = p.idProducto
        WHERE tv.idEmpleado = @p_id_empleado;

        -- Vaciar carrito temporal para el empleado
        DELETE FROM Temp_Ventas WHERE idEmpleado = @p_id_empleado;

        COMMIT TRANSACTION;
    END TRY
    BEGIN CATCH
        ROLLBACK TRANSACTION;
        THROW; -- Re-lanza el error para manejarlo en la aplicaci�n
    END CATCH
END;



CREATE PROCEDURE ActualizarProveedor
    @p_id_proveedor INT,
    @p_nombre VARCHAR(100)
AS
BEGIN
    UPDATE Proveedores
    SET Nombre = @p_nombre
    WHERE idProveedor = @p_id_proveedor;
END;



CREATE PROCEDURE EliminarProveedor
    @p_id_proveedor INT
AS
BEGIN
    UPDATE Proveedores
    SET Estado = 'Inactivo'
    WHERE idProveedor = @p_id_proveedor;
END;



CREATE PROCEDURE AgregarProveedor
    @p_nombre VARCHAR(100)
AS
BEGIN
    INSERT INTO Proveedores (Nombre)
    VALUES (@p_nombre);
END;


CREATE PROCEDURE RecuperarCliente
    @p_idCliente INT
AS
BEGIN
    DECLARE @persona_id INT;

    -- Obtener idPersona correspondiente al cliente
    SELECT @persona_id = idPersona
    FROM Clientes
    WHERE idCliente = @p_idCliente;

    -- Recuperaci�n l�gica en Personas
    UPDATE Personas
    SET Estatus = 'Activo'
    WHERE idPersona = @persona_id;
END;



CREATE PROCEDURE EliminarCliente
    @p_idCliente INT
AS
BEGIN
    DECLARE @persona_id INT;

    -- Obtener idPersona correspondiente al cliente
    SELECT @persona_id = idPersona
    FROM Clientes
    WHERE idCliente = @p_idCliente;

    -- Baja l�gica en Personas
    UPDATE Personas
    SET Estatus = 'Inactivo'
    WHERE idPersona = @persona_id;
END;



CREATE PROCEDURE ActualizarCliente
    @p_idCliente INT,
    @p_Nombre VARCHAR(50),
    @p_Paterno VARCHAR(50),
    @p_Materno VARCHAR(50),
    @p_Telefono VARCHAR(10),
    @p_Email VARCHAR(50),
    @p_Edad SMALLINT,
    @p_Sexo CHAR(1),  -- Reemplazo de ENUM('H','M')
    @p_idDomicilio INT,
    @p_Credito DECIMAL(10,2),
    @p_Limite DECIMAL(10,2),
    @p_idDescuento INT
AS
BEGIN
    DECLARE @persona_id INT;

    -- Obtener idPersona correspondiente al cliente
    SELECT @persona_id = idPersona
    FROM Clientes
    WHERE idCliente = @p_idCliente;

    -- Actualizar Persona
    UPDATE Personas
    SET 
        Nombre = @p_Nombre,
        Paterno = @p_Paterno,
        Materno = @p_Materno,
        Telefono = @p_Telefono,
        Email = @p_Email,
        Edad = @p_Edad,
        Sexo = @p_Sexo,
        idDomicilio = @p_idDomicilio
    WHERE idPersona = @persona_id;

    -- Actualizar Cliente
    UPDATE Clientes
    SET 
        Credito = @p_Credito,
        Limite = @p_Limite,
        idDescuento = @p_idDescuento
    WHERE idCliente = @p_idCliente;
END;



CREATE PROCEDURE AgregarCliente
    @p_Nombre VARCHAR(50),
    @p_Paterno VARCHAR(50),
    @p_Materno VARCHAR(50),
    @p_Telefono VARCHAR(10),
    @p_Email VARCHAR(50),
    @p_Edad SMALLINT,
    @p_Sexo CHAR(1),  -- Reemplazo de ENUM('H','M')
    @p_idDomicilio INT,
    @p_Credito DECIMAL(10,2),
    @p_Limite DECIMAL(10,2),
    @p_idDescuento INT
AS
BEGIN
    DECLARE @nuevo_idPersona INT;

    -- Insertar en Personas
    INSERT INTO Personas (
        Nombre, Paterno, Materno, Telefono, Email, Edad, Sexo, Estatus, idDomicilio
    ) VALUES (
        @p_Nombre, @p_Paterno, @p_Materno, @p_Telefono, @p_Email, @p_Edad, @p_Sexo, 'Activo', @p_idDomicilio
    );

    -- Obtener el ID generado
    SET @nuevo_idPersona = SCOPE_IDENTITY();

    -- Insertar en Clientes
    INSERT INTO Clientes (
        Credito, Limite, idPersona, idDescuento
    ) VALUES (
        @p_Credito, @p_Limite, @nuevo_idPersona, @p_idDescuento
    );
END;



CREATE PROCEDURE RecuperarEmpleado
    @p_idEmpleado INT
AS
BEGIN
    DECLARE @persona_id INT;

    -- Obtener el idPersona vinculado al empleado
    SELECT @persona_id = idPersona
    FROM Empleados
    WHERE idEmpleado = @p_idEmpleado;

    -- Marcar a la persona como activa
    UPDATE Personas
    SET Estatus = 'Activo'
    WHERE idPersona = @persona_id;
END;



CREATE PROCEDURE EliminarEmpleado
    @p_idEmpleado INT
AS
BEGIN
    DECLARE @persona_id INT;

    -- Obtener el idPersona vinculado al empleado
    SELECT @persona_id = idPersona
    FROM Empleados
    WHERE idEmpleado = @p_idEmpleado;

    -- Marcar a la persona como inactiva
    UPDATE Personas
    SET Estatus = 'Inactivo'
    WHERE idPersona = @persona_id;
END;




CREATE PROCEDURE ActualizarEmpleado
    @p_idEmpleado INT,
    @p_Nombre VARCHAR(50),
    @p_Paterno VARCHAR(50),
    @p_Materno VARCHAR(50),
    @p_Telefono VARCHAR(10),
    @p_Email VARCHAR(50),
    @p_Edad SMALLINT,
    @p_Sexo CHAR(1),  -- 'H' o 'M'
    @p_idDomicilio INT,
    @p_Puesto VARCHAR(20),  -- Validar desde l�gica o CHECK constraint
    @p_RFC VARCHAR(13),
    @p_NumeroSeguroSocial VARCHAR(11),
    @p_Usuario VARCHAR(255)
AS
BEGIN
    DECLARE @persona_id INT;

    -- Obtener el idPersona vinculado al empleado
    SELECT @persona_id = idPersona
    FROM Empleados
    WHERE idEmpleado = @p_idEmpleado;

    -- Actualizar en Personas
    UPDATE Personas
    SET 
        Nombre = @p_Nombre,
        Paterno = @p_Paterno,
        Materno = @p_Materno,
        Telefono = @p_Telefono,
        Email = @p_Email,
        Edad = @p_Edad,
        Sexo = @p_Sexo,
        idDomicilio = @p_idDomicilio
    WHERE idPersona = @persona_id;

    -- Actualizar en Empleados (sin contrase�a)
    UPDATE Empleados
    SET 
        Puesto = @p_Puesto,
        RFC = @p_RFC,
        NumeroSeguroSocial = @p_NumeroSeguroSocial,
        Usuario = @p_Usuario
    WHERE idEmpleado = @p_idEmpleado;
END;




CREATE PROCEDURE AgregarEmpleado
    @p_Nombre VARCHAR(50),
    @p_Paterno VARCHAR(50),
    @p_Materno VARCHAR(50),
    @p_Telefono VARCHAR(10),
    @p_Email VARCHAR(50),
    @p_Edad SMALLINT,
    @p_Sexo CHAR(1),  -- 'H' o 'M'
    @p_idDomicilio INT,
    @p_Puesto VARCHAR(20),  -- Validar en la l�gica o con CHECK
    @p_RFC VARCHAR(13),
    @p_NumeroSeguroSocial VARCHAR(11),
    @p_Usuario VARCHAR(255),
    @p_Contrasena VARCHAR(255)
AS
BEGIN
    DECLARE @nuevo_idPersona INT;

    -- Insertar en Personas
    INSERT INTO Personas (
        Nombre, Paterno, Materno, Telefono, Email, Edad, Sexo, Estatus, idDomicilio
    ) VALUES (
        @p_Nombre, @p_Paterno, @p_Materno, @p_Telefono, @p_Email, @p_Edad, @p_Sexo, 'Activo', @p_idDomicilio
    );

    SET @nuevo_idPersona = SCOPE_IDENTITY();

    -- Insertar en Empleados
    INSERT INTO Empleados (
        Puesto, RFC, NumeroSeguroSocial, Usuario, Contrase�a, idPersona
    ) VALUES (
        @p_Puesto, @p_RFC, @p_NumeroSeguroSocial, @p_Usuario, @p_Contrasena, @nuevo_idPersona
    );
END;






CREATE PROCEDURE BuscarProductoPorNombre
    @p_nombre VARCHAR(100)
AS
BEGIN
    SELECT * 
    FROM Productos
    WHERE Nombre = @p_nombre AND Estado = 'Activo';
END;


CREATE PROCEDURE EliminarProducto
    @p_idProducto INT
AS
BEGIN
    UPDATE Productos
    SET Estado = 'Inactivo'
    WHERE idProducto = @p_idProducto;
END;




CREATE PROCEDURE ActualizarProductoCompleto
    @p_idProducto INT,
    @p_Nombre VARCHAR(100),
    @p_PrecioCompra DECIMAL(10,2),
    @p_PrecioVenta DECIMAL(10,2),
    @p_CodigoBarras BIGINT,
    @p_Stock INT,
    @p_idCategoria INT,
    @p_idProveedor INT
AS
BEGIN
    UPDATE Productos
    SET 
        Nombre = @p_Nombre,
        PrecioCompra = @p_PrecioCompra,
        PrecioVenta = @p_PrecioVenta,
        CodigoBarras = @p_CodigoBarras,
        Stock = @p_Stock,
        idCategoria = @p_idCategoria,
        idProveedor = @p_idProveedor
    WHERE idProducto = @p_idProducto;
END;



CREATE PROCEDURE AgregarProducto
    @p_Nombre VARCHAR(100),
    @p_PrecioCompra DECIMAL(10,2),
    @p_PrecioVenta DECIMAL(10,2),
    @p_CodigoBarras BIGINT,
    @p_Stock INT,
    @p_idCategoria INT,
    @p_idProveedor INT
AS
BEGIN
    INSERT INTO Productos (
        Nombre, PrecioCompra, PrecioVenta, CodigoBarras,
        Stock, Estado, idCategoria, idProveedor
    )
    VALUES (
        @p_Nombre, @p_PrecioCompra, @p_PrecioVenta, @p_CodigoBarras,
        @p_Stock, 'Activo', @p_idCategoria, @p_idProveedor
    );
END;
