USE ProyectoHerreriaUG;
GO 
-- Verifica si la vista existe y elimínala
IF OBJECT_ID('Vista_AsentamientosPorCP', 'V') IS NOT NULL
    DROP VIEW Vista_AsentamientosPorCP;
GO

CREATE VIEW Vista_AsentamientosPorCP AS
SELECT 
    cp.c_CP,
    cp.d_codigo AS CodigoPostal,
    a.d_asenta AS Asentamiento,
    a.d_tipo_asenta AS TipoAsentamiento
FROM 
    CodigosPostales cp
JOIN 
    Asentamiento a ON cp.c_tipo_asenta = a.c_tipo_asenta;
GO






-- Verifica si la vista existe y elimínala
IF OBJECT_ID('VistaObtenerCarritoPorEmpleado', 'V') IS NOT NULL
    DROP VIEW VistaObtenerCarritoPorEmpleado;
GO

CREATE VIEW VistaObtenerCarritoPorEmpleado AS
SELECT 
    t.idEmpleado,
    p.Nombre AS Producto,
    t.Cantidad,
    (p.PrecioVenta * t.Cantidad) AS Total
FROM Temp_Ventas t
JOIN Productos p ON t.idProducto = p.idProducto;
GO

-- Verifica si la vista existe y elimínala
IF OBJECT_ID('Vista_Todos_Proveedores', 'V') IS NOT NULL
    DROP VIEW Vista_Todos_Proveedores;
GO

CREATE VIEW Vista_Todos_Proveedores AS
SELECT 
    idProveedor, 
    Nombre
FROM Proveedores;
GO




-- Verifica si la vista existe y elimínala
IF OBJECT_ID('VistaPedidosEnviados', 'V') IS NOT NULL
    DROP VIEW VistaPedidosEnviados;
GO

-- Crear la vista
CREATE VIEW VistaPedidosEnviados AS
SELECT 
    p.idPedido,
    p.Fecha,
    p.Hora,
    p.Estatus,
    cl.idCliente,
    pe.Nombre AS NombreCliente
FROM Pedidos p
JOIN Clientes cl ON p.idCliente = cl.idCliente
JOIN Personas pe ON cl.idPersona = pe.idPersona
WHERE p.Estatus = 'Enviado';
GO





-- Verifica si la vista existe y elimínala
IF OBJECT_ID('VistaPedidosAceptados', 'V') IS NOT NULL
    DROP VIEW VistaPedidosAceptados;
GO

-- Crear la vista
CREATE VIEW VistaPedidosAceptados AS
SELECT 
    p.idPedido,
    p.Fecha,
    p.Hora,
    p.Estatus,
    cl.idCliente,
    pe.Nombre AS NombreCliente
FROM Pedidos p
JOIN Clientes cl ON p.idCliente = cl.idCliente
JOIN Personas pe ON cl.idPersona = pe.idPersona
WHERE p.Estatus = 'Aceptado';
GO




-- Verifica si la vista existe y elimínala
IF OBJECT_ID('VistaPedidosPendientes', 'V') IS NOT NULL
    DROP VIEW VistaPedidosPendientes;
GO

-- Crear la vista
CREATE VIEW VistaPedidosPendientes AS
SELECT 
    p.idPedido,
    p.Fecha,
    p.Hora,
    p.Estatus,
    cl.idCliente,
    pe.Nombre AS NombreCliente
FROM Pedidos p
JOIN Clientes cl ON p.idCliente = cl.idCliente
JOIN Personas pe ON cl.idPersona = pe.idPersona
WHERE p.Estatus = 'Pendiente';
GO




-- Verifica si la vista existe y elimínala
IF OBJECT_ID('VistaClientesInactivos', 'V') IS NOT NULL
    DROP VIEW VistaClientesInactivos;
GO

-- Crear la vista
CREATE VIEW VistaClientesInactivos AS
SELECT 
    c.idCliente,
    p.Nombre, 
    p.Paterno, 
    p.Materno,
    p.Email, 
    p.Telefono,
    c.Credito, 
    c.Limite,
    d.Categoria AS TipoCliente
FROM Clientes c
JOIN Personas p ON c.idPersona = p.idPersona
LEFT JOIN Descuentos d ON c.idDescuento = d.idDescuento
WHERE p.Estatus = 'Inactivo';
GO



-- Verifica si la vista existe y elimínala
IF OBJECT_ID('VistaClientesActivos', 'V') IS NOT NULL
    DROP VIEW VistaClientesActivos;
GO

-- Crear la vista
CREATE VIEW VistaClientesActivos AS
SELECT 
    c.idCliente,
    p.Nombre, 
    p.Paterno, 
    p.Materno,
    p.Email, 
    p.Telefono,
    c.Credito, 
    c.Limite,
    d.Categoria AS TipoCliente
FROM Clientes c
JOIN Personas p ON c.idPersona = p.idPersona
LEFT JOIN Descuentos d ON c.idDescuento = d.idDescuento
WHERE p.Estatus = 'Activo';
GO




-- Verifica si la vista existe y elimínala
IF OBJECT_ID('VistaProductosInactivos', 'V') IS NOT NULL
    DROP VIEW VistaProductosInactivos;
GO

-- Crear la vista
CREATE VIEW VistaProductosInactivos AS
SELECT 
    p.idProducto,
    p.Nombre AS Producto,
    c.Nombre AS Categoria,
    pr.Nombre AS Proveedor,
    p.Stock,
    p.PrecioCompra,
    p.PrecioVenta,
    p.Estado
FROM Productos p
JOIN Categorias c ON p.idCategoria = c.idCategoria
JOIN Proveedores pr ON p.idProveedor = pr.idProveedor
WHERE p.Estado = 'Inactivo';
GO


-- Verifica si la vista existe y elimínala
IF OBJECT_ID('VistaProductosActivos', 'V') IS NOT NULL
    DROP VIEW VistaProductosActivos;
GO

-- Crear la vista
CREATE VIEW VistaProductosActivos AS
SELECT 
    p.idProducto,
    p.Nombre AS Producto,
    c.Nombre AS Categoria,
    pr.Nombre AS Proveedor,
    p.Stock,
    p.PrecioCompra,
    p.PrecioVenta,
    p.Estado
FROM Productos p
JOIN Categorias c ON p.idCategoria = c.idCategoria
JOIN Proveedores pr ON p.idProveedor = pr.idProveedor
WHERE p.Estado = 'Activo';
GO


-- Verifica si la vista existe y elimínala
IF OBJECT_ID('VistaEmpleadosInactivos', 'V') IS NOT NULL
    DROP VIEW VistaEmpleadosInactivos;
GO

-- Crear la vista
CREATE VIEW VistaEmpleadosInactivos AS
SELECT 
    e.idEmpleado,
    p.Nombre,
    p.Paterno,
    p.Materno,
    p.Telefono,
    p.Email,
    p.Edad,
    p.Sexo,
    p.idDomicilio,
    e.Puesto,
    e.RFC,
    e.NumeroSeguroSocial,
    e.Usuario
FROM Empleados e
JOIN Personas p ON e.idPersona = p.idPersona
WHERE p.Estatus = 'Inactivo';
GO



-- Verifica si la vista existe y elimínala
IF OBJECT_ID('VistaEmpleadosActivos', 'V') IS NOT NULL
    DROP VIEW VistaEmpleadosActivos;
GO

-- Crear la vista
CREATE VIEW VistaEmpleadosActivos AS
SELECT 
    e.idEmpleado,
    p.Nombre,
    p.Paterno,
    p.Materno,
    p.Telefono,
    p.Email,
    p.Edad,
    p.Sexo,
    p.idDomicilio,
    e.Puesto,
    e.RFC,
    e.NumeroSeguroSocial,
    e.Usuario
FROM Empleados e
JOIN Personas p ON e.idPersona = p.idPersona
WHERE p.Estatus = 'Activo';
GO




-- Verifica si la vista existe y elimínala
IF OBJECT_ID('VistaArticuloSimplificado', 'V') IS NOT NULL
    DROP VIEW VistaArticuloSimplificado;
GO

-- Crear la vista
CREATE VIEW VistaArticuloSimplificado AS
SELECT 
    Nombre, 
    Stock AS Cantidad, 
    PrecioVenta AS Precio
FROM Productos;
GO





-- Verifica si la vista existe y elimínala
IF OBJECT_ID('VistaVentasDiarias', 'V') IS NOT NULL
    DROP VIEW VistaVentasDiarias;
GO

-- Crear la vista
CREATE VIEW VistaVentasDiarias AS
SELECT 
    v.idVenta AS NumeroVenta,
    CAST(v.Fecha AS DATE) AS Fecha,
    CAST(v.Fecha AS TIME) AS Hora,

    ISNULL(emp.Nombre, '') + ' ' + ISNULL(emp.Paterno, '') + ' ' + ISNULL(emp.Materno, '') AS Empleado,
    ISNULL(cli.Nombre, '') + ' ' + ISNULL(cli.Paterno, '') + ' ' + ISNULL(cli.Materno, '') AS Cliente,

    p.Nombre AS Producto,
    dv.Cantidad,
    (dv.Total / NULLIF(dv.Cantidad, 0)) AS PrecioUnitario,
    dv.Total AS Subtotal,
    dv.IVA,
    dv.IEPS,
    ISNULL(dv.idDescuento, 0) AS Descuento,

    v.Monto AS TotalVenta,
    v.TipoPago,
    v.Estatus
FROM Ventas v
JOIN DetalleVenta dv ON v.idVenta = dv.idVenta
JOIN Productos p ON dv.idProducto = p.idProducto

JOIN Empleados e ON v.idEmpleado = e.idEmpleado
JOIN Personas emp ON e.idPersona = emp.idPersona

JOIN Clientes c ON v.idCliente = c.idCliente
JOIN Personas cli ON c.idPersona = cli.idPersona;
GO
