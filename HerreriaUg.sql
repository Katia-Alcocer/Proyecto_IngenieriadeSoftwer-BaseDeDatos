CREATE DATABASE ProyectoHerreriaUG;
GO

USE ProyectoHerreriaUG;
GO

-- Tablas

CREATE TABLE Pais (
    id_Pais INT IDENTITY(1,1) PRIMARY KEY,
    Nombre VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE Estado (
    c_estado INT IDENTITY(1,1) PRIMARY KEY,
    d_Estado VARCHAR(100) NOT NULL UNIQUE,
    id_Pais INT NOT NULL,
    FOREIGN KEY (id_Pais) REFERENCES Pais(id_Pais)
);

CREATE TABLE Ciudad (
    c_cve_ciudad INT IDENTITY(1,1) PRIMARY KEY,
    d_ciudad VARCHAR(100) NOT NULL,
    c_oficina INT,
    c_estado INT NOT NULL,
    FOREIGN KEY (c_estado) REFERENCES Estado(c_estado)
);

CREATE TABLE Municipio (
    c_mnpio INT IDENTITY(1,1) PRIMARY KEY,
    D_mnpio VARCHAR(100) NOT NULL,
    c_cve_ciudad INT,
    FOREIGN KEY (c_cve_ciudad) REFERENCES Ciudad(c_cve_ciudad)
);

CREATE TABLE Asentamiento (
    c_tipo_asenta INT IDENTITY(1,1) PRIMARY KEY,
    d_asenta VARCHAR(100),
    d_tipo_asenta VARCHAR(100),
    id_asenta_cpcons INT,
    d_zona VARCHAR(100),
    c_mnpio INT NOT NULL,
    FOREIGN KEY (c_mnpio) REFERENCES Municipio(c_mnpio)
);

CREATE TABLE CodigosPostales (
    c_CP INT IDENTITY(1,1) PRIMARY KEY,
    d_codigo VARCHAR(10) NOT NULL,
    d_CP VARCHAR(10),
    c_tipo_asenta INT NOT NULL,
    FOREIGN KEY (c_tipo_asenta) REFERENCES Asentamiento(c_tipo_asenta)
);

CREATE TABLE Domicilios (
    idDomicilio INT IDENTITY(1,1) PRIMARY KEY,
    Calle VARCHAR(50) NOT NULL,
    Numero INT NOT NULL,
    c_CP INT NOT NULL,
    FOREIGN KEY (c_CP) REFERENCES CodigosPostales(c_CP)
);

CREATE TABLE Personas (
    idPersona INT IDENTITY(1,1) PRIMARY KEY,
    Nombre VARCHAR(50) NOT NULL,
    Paterno VARCHAR(50) NOT NULL,
    Materno VARCHAR(50) NOT NULL,
    Telefono VARCHAR(10) NOT NULL UNIQUE,
    Email VARCHAR(50) NOT NULL UNIQUE,
    Edad SMALLINT NOT NULL CHECK (Edad > 0 AND Edad < 100),
    Sexo CHAR(1) CHECK (Sexo IN ('H', 'M')) NOT NULL,
    Estatus VARCHAR(10) CHECK (Estatus IN ('Activo', 'Inactivo')) NOT NULL DEFAULT 'Activo',
    idDomicilio INT,
    FOREIGN KEY (idDomicilio) REFERENCES Domicilios(idDomicilio) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Descuentos (
    idDescuento INT IDENTITY(1,1) PRIMARY KEY,
    Categoria VARCHAR(100) NOT NULL,
    Porcentaje DECIMAL(5,2) NOT NULL CHECK (Porcentaje BETWEEN 0 AND 100)
);

CREATE TABLE Clientes (
    idCliente INT IDENTITY(1,1) PRIMARY KEY,
    Credito DECIMAL(10,2) NOT NULL,
    Limite DECIMAL(10,2) NOT NULL,
    idPersona INT NOT NULL,
    idDescuento INT,
    CHECK (Credito <= Limite),
    FOREIGN KEY (idPersona) REFERENCES Personas(idPersona) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (idDescuento) REFERENCES Descuentos(idDescuento) ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE Empleados (
    idEmpleado INT IDENTITY(1,1) PRIMARY KEY,
    Puesto VARCHAR(20) CHECK (Puesto IN ('Administrador', 'Cajero', 'Agente de Venta')) NOT NULL,
    RFC VARCHAR(13) NOT NULL UNIQUE,
    NumeroSeguroSocial VARCHAR(11) NOT NULL UNIQUE,
    Usuario VARCHAR(255) NOT NULL UNIQUE,
    Contraseña VARCHAR(255) NOT NULL,
    idPersona INT NOT NULL,
    FOREIGN KEY (idPersona) REFERENCES Personas(idPersona) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Proveedores (
    idProveedor INT IDENTITY(1,1) PRIMARY KEY,
    Estado VARCHAR(10) CHECK (Estado IN ('Activo', 'Inactivo')) NOT NULL DEFAULT 'Activo',
    Nombre VARCHAR(100) NOT NULL
);

CREATE TABLE Categorias (
    idCategoria INT IDENTITY(1,1) PRIMARY KEY,
    Nombre VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE Productos (
    idProducto INT IDENTITY(1,1) PRIMARY KEY,
    Nombre VARCHAR(100) NOT NULL,
    PrecioCompra DECIMAL(10,2) NOT NULL,
    PrecioVenta DECIMAL(10,2) NOT NULL,
    CodigoBarras BIGINT,
    Stock INT NOT NULL DEFAULT 0,
    Estado VARCHAR(10) CHECK (Estado IN ('Activo', 'Inactivo')) NOT NULL DEFAULT 'Activo',
    idCategoria INT NOT NULL,
    idProveedor INT NOT NULL,
    CHECK (PrecioVenta >= PrecioCompra),
    CHECK (Stock >= 0),
    FOREIGN KEY (idCategoria) REFERENCES Categorias(idCategoria)  ON UPDATE CASCADE,
    FOREIGN KEY (idProveedor) REFERENCES Proveedores(idProveedor) ON UPDATE CASCADE
);

CREATE TABLE Ventas (
    idVenta INT IDENTITY(1,1) PRIMARY KEY,
    Monto DECIMAL(10,2) NOT NULL,
    Fecha DATETIME NOT NULL DEFAULT GETDATE(),
    Subtotal DECIMAL(10,2) NOT NULL,
    IVA DECIMAL(10,2) NOT NULL,
    IEPS DECIMAL(10,2) NOT NULL,
    CantidadProductos INT NOT NULL CHECK (CantidadProductos > 0),
    TipoPago VARCHAR(20) CHECK (TipoPago IN ('Contado', 'Cheque', 'Transferencia', 'Credito')) NOT NULL,
    Estatus VARCHAR(30) CHECK (Estatus IN ('Cancelada', 'Pagada', 'En Espera de Pago')) NOT NULL DEFAULT 'En Espera de Pago',
    idCliente INT NOT NULL,
    idEmpleado INT NOT NULL,
        FOREIGN KEY (idCliente) REFERENCES Clientes(idCliente),
    FOREIGN KEY (idEmpleado) REFERENCES Empleados(idEmpleado) 
);

CREATE TABLE DetalleVenta (
    idDetalleVenta INT IDENTITY(1,1) PRIMARY KEY,
    Cantidad INT NOT NULL CHECK (Cantidad > 0),
    Total DECIMAL(10,2) NOT NULL,
    IVA DECIMAL(10,2) NOT NULL,
    IEPS DECIMAL(10,2) NOT NULL,
    idVenta INT NOT NULL,
    idProducto INT NOT NULL,
    idDescuento INT,
    FOREIGN KEY (idVenta) REFERENCES Ventas(idVenta) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (idProducto) REFERENCES Productos(idProducto) ON UPDATE CASCADE,
    FOREIGN KEY (idDescuento) REFERENCES Descuentos(idDescuento)  ON UPDATE CASCADE
);

CREATE TABLE Temp_Ventas (
    idEmpleado INT NOT NULL,
    idProducto INT NOT NULL,
    Cantidad INT NOT NULL CHECK (Cantidad > 0),
    PRIMARY KEY (idEmpleado, idProducto),
    FOREIGN KEY (idEmpleado) REFERENCES Empleados(idEmpleado)  ON UPDATE CASCADE,
    FOREIGN KEY (idProducto) REFERENCES Productos(idProducto) ON UPDATE CASCADE
);

CREATE TABLE HistorialModificaciones (
    idHistorial INT IDENTITY(1,1) PRIMARY KEY,
    Movimiento VARCHAR(50),
    TablaAfectada VARCHAR(100) NOT NULL,
    ColumnaAfectada VARCHAR(100) NOT NULL,
    DatoAnterior TEXT,
    DatoNuevo TEXT,
    Fecha DATE NOT NULL DEFAULT CAST(GETDATE() AS DATE),
    Hora TIME NOT NULL DEFAULT CAST(GETDATE() AS TIME),
    idEmpleado INT NOT NULL,
    FOREIGN KEY (idEmpleado) REFERENCES Empleados(idEmpleado) ON UPDATE CASCADE
);

CREATE TABLE Finanzas (
    idFinanza INT IDENTITY(1,1) PRIMARY KEY,
    idVenta INT NOT NULL,
    TotalVenta DECIMAL(10,2) NOT NULL,
    Invertido DECIMAL(10,2) NOT NULL,
    Ganancia AS (TotalVenta - Invertido) PERSISTED,
    FOREIGN KEY (idVenta) REFERENCES Ventas(idVenta) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Pedidos (
    idPedido INT IDENTITY(1,1) PRIMARY KEY,
    Fecha DATE NOT NULL DEFAULT CAST(GETDATE() AS DATE),
    Hora TIME NOT NULL DEFAULT CAST(GETDATE() AS TIME),
    Estatus VARCHAR(20) CHECK (Estatus IN ('Pendiente', 'Aceptado', 'Enviado', 'Cancelado')) NOT NULL DEFAULT 'Pendiente',
    idCliente INT NOT NULL,
    idEmpleado INT,
    FOREIGN KEY (idCliente) REFERENCES Clientes(idCliente) ,
    FOREIGN KEY (idEmpleado) REFERENCES Empleados(idEmpleado)
);

CREATE TABLE DetallePedidos (
    idDetallePedido INT IDENTITY(1,1) PRIMARY KEY,
    idPedido INT NOT NULL,
    idProducto INT NOT NULL,
    Cantidad INT NOT NULL CHECK (Cantidad > 0),
    PrecioUnitario DECIMAL(10,2) NOT NULL,
    Subtotal AS (Cantidad * PrecioUnitario) PERSISTED,
    FOREIGN KEY (idPedido) REFERENCES Pedidos(idPedido) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (idProducto) REFERENCES Productos(idProducto) ON UPDATE CASCADE
);
