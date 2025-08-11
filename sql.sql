-- Cambiar el tamaño del atributo 'descripcion' de VARCHAR(3) a VARCHAR(4)
ALTER TABLE
    semestre
MODIFY
    COLUMN descripcion VARCHAR(4);

-- Insertar las nuevas filas
INSERT INTO
    semestre (descripcion)
VALUES
    ('VII'),
    ('VIII');

-- Alterar la tabla para agregar las nuevas columnas
ALTER TABLE
    unidad_didactica
ADD
    COLUMN codigos_ud_predecesora VARCHAR(255),
    -- Columna para códigos de predecesoras
ADD
    COLUMN codigo_correlativo VARCHAR(3);

-- Columna para código correlativo
-------------solicitudes de programacion de UD--------------------
CREATE TABLE solicitud_programacion_ud (
    id INT AUTO_INCREMENT PRIMARY KEY,
    -- Identificador único de la solicitud
    id_unidad_didactica INT NOT NULL,
    -- ID de la unidad didáctica
    id_docente INT NOT NULL,
    -- ID del docente de teoría
    id_docente_practica INT NOT NULL,
    -- ID del docente de práctica
    id_periodo_acad INT NOT NULL,
    -- ID del periodo academico
    estado VARCHAR(20) NOT NULL DEFAULT 'Pendiente',
    CONSTRAINT fk_unidad_didactica FOREIGN KEY (id_unidad_didactica) REFERENCES unidad_didactica(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_docente FOREIGN KEY (id_docente) REFERENCES docente(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_docente_practica_ud FOREIGN KEY (id_docente_practica) REFERENCES docente(id) ON DELETE CASCADE ON UPDATE CASCADE
);

ALTER TABLE
    estudiante
ADD
    COLUMN nivel_formativo VARCHAR(50),
ADD
    COLUMN fecha_egreso DATE,
ADD
    COLUMN ubigeo_ie VARCHAR(6),
ADD
    COLUMN departamento_ie VARCHAR(50),
ADD
    COLUMN provincia_ie VARCHAR(50),
ADD
    COLUMN distrito_ie VARCHAR(50),
ADD
    COLUMN tipo_ie VARCHAR(50),
ADD
    COLUMN codigo_mod_ie VARCHAR(12),
ADD
    COLUMN nombre_ie VARCHAR(100),
ADD
    COLUMN tipo_gestion_ie VARCHAR(50),
ADD
    COLUMN anio_egreso_ie YEAR;

CREATE TABLE IF NOT EXISTS periodo_matriculado (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_estudiante INT NOT NULL,
    id_periodo VARCHAR(10) NOT NULL,
    FOREIGN KEY (id_estudiante) REFERENCES estudiante(id)
);

CREATE TABLE convalidacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_estudiante INT NOT NULL,
    id_periodo_academico INT NOT NULL,
    resolucion VARCHAR(255) NOT NULL,
    archivo_resolucion VARCHAR(255),
    tipo VARCHAR(50) NOT NULL,
    id_semestre INT NOT NULL,
    id_unidad_didactica INT NOT NULL,
    programa_estudios_origen VARCHAR(255) NOT NULL,
    unidad_didactica_origen VARCHAR(255) NOT NULL,
    calificacion BLOB NOT NULL,
    CONSTRAINT fk_convalidacion_estudiante FOREIGN KEY (id_estudiante) REFERENCES estudiante(id),
    CONSTRAINT fk_convalidacion_periodo_academico FOREIGN KEY (id_periodo_academico) REFERENCES periodo_academico(id),
    CONSTRAINT fk_convalidacion_semestre FOREIGN KEY (id_semestre) REFERENCES semestre(id),
    CONSTRAINT fk_convalidacion_unidad_didactica FOREIGN KEY (id_unidad_didactica) REFERENCES unidad_didactica(id)
);

ALTER TABLE
    sistema
ADD
    COLUMN ruc VARCHAR(20) NULL,
ADD
    COLUMN region VARCHAR(100) NULL;

CREATE TABLE efsrt (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_estudiante INT NOT NULL,
    id_programa INT NOT NULL,
    id_modulo INT NOT NULL,
    lugar VARCHAR(255) NOT NULL,
    fecha_inicio DATE NOT NULL,
    periodo_lectivo VARCHAR(50) NULL,
    file_resolucion VARCHAR(255) NULL,
    resolucion VARCHAR(255) NULL,
    calificacion BLOB NULL,
    estado INT NOT NULL DEFAULT 1,
    id_docente INT NULL,
    informe VARCHAR(200) NULL,
    carta_presentacion VARCHAR(200) NULL,
    cargo_responsable VARCHAR(200) NULL,
    responsable VARCHAR(255) NULL,
    observacion TEXT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_estudiante) REFERENCES estudiante(id),
    FOREIGN KEY (id_programa) REFERENCES programa_estudios(id),
    FOREIGN KEY (id_modulo) REFERENCES modulo_profesional(id)
) CREATE TABLE `asistencia_administrativo` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `fecha_asistencia` date NOT NULL,
    `hora_asistencia` time NOT NULL,
    `docente_id` int(11) NOT NULL,
    `foto_url` varchar(100) DEFAULT NULL,
    `permiso` tinyint(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `asistencia_administrativo_docente_FK` (`docente_id`),
    CONSTRAINT `asistencia_administrativo_docente_FK` FOREIGN KEY (`docente_id`) REFERENCES `docente` (`id`)
);

CREATE TABLE solicitud_matricula (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_configuracion_matricula INT,
    id_estudiante INT,
    boucher VARCHAR(200) NULL,
    observacion TEXT NULL,
    programas varchar(200) NOT NULL,
    estado BIT DEFAULT 0,
    FOREIGN KEY (id_estudiante) REFERENCES estudiante(id),
    FOREIGN KEY (id_configuracion_matricula) REFERENCES configuracion_matricula(id)
);

CREATE TABLE `criterio_evaluacion_auditoria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_criterio_evaluacion` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `nombre_usuario` varchar(130) NOT NULL,
  `accion` varchar(200) NOT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_criterio_evaluacion` (`id_criterio_evaluacion`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `criterio_evaluacion_auditoria_ibfk_1` FOREIGN KEY (`id_criterio_evaluacion`) REFERENCES `criterio_evaluacion` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `criterio_evaluacion_auditoria_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `docente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_criterio_evaluacion` int(11) DEFAULT NULL,
    `id_usuario` int(11) DEFAULT NULL,
    `nombre_usuario` varchar(130) NOT NULL,
    `accion` varchar(50) NOT NULL,
    `fecha_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `id_criterio_evaluacion` (`id_criterio_evaluacion`),
    KEY `id_usuario` (`id_usuario`),
    CONSTRAINT `criterio_evaluacion_auditoria_ibfk_1` FOREIGN KEY (`id_criterio_evaluacion`) REFERENCES `criterio_evaluacion` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE,
        CONSTRAINT `criterio_evaluacion_auditoria_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `docente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE `efsrt_auditoria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_efsrt` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `nombre_usuario` varchar(130) NOT NULL,
  `accion` varchar(200) NOT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_efsrt` (`id_efsrt`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `efsrt_auditoria_ibfk_1` FOREIGN KEY (`id_efsrt`) REFERENCES `efsrt` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `efsrt_auditoria_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `docente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE `convalidacion_auditoria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_convalidacion` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `nombre_usuario` varchar(130) NOT NULL,
  `accion` varchar(200) NOT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_convalidacion` (`id_convalidacion`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `convalidaciones_auditoria_ibfk_1` FOREIGN KEY (`id_convalidacion`) REFERENCES `convalidacion` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `convalidaciones_auditoria_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `docente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_criterio_evaluacion` int(11) DEFAULT NULL,
    `id_usuario` int(11) DEFAULT NULL,
    `nombre_usuario` varchar(130) NOT NULL,
    `accion` varchar(50) NOT NULL,
    `fecha_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `id_criterio_evaluacion` (`id_criterio_evaluacion`),
    KEY `id_usuario` (`id_usuario`),
    CONSTRAINT `criterio_evaluacion_auditoria_ibfk_1` FOREIGN KEY (`id_criterio_evaluacion`) REFERENCES `criterio_evaluacion` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE,
        CONSTRAINT `criterio_evaluacion_auditoria_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `docente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE `efsrt_auditoria` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_efsrt` int(11) DEFAULT NULL,
    `id_usuario` int(11) DEFAULT NULL,
    `nombre_usuario` varchar(130) NOT NULL,
    `accion` varchar(50) NOT NULL,
    `fecha_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `id_efsrt` (`id_efsrt`),
    KEY `id_usuario` (`id_usuario`),
    CONSTRAINT `efsrt_auditoria_ibfk_1` FOREIGN KEY (`id_efsrt`) REFERENCES `efsrt` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE,
        CONSTRAINT `efsrt_auditoria_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `docente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE `convalidacion_auditoria` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_convalidacion` int(11) DEFAULT NULL,
    `id_usuario` int(11) DEFAULT NULL,
    `nombre_usuario` varchar(130) NOT NULL,
    `accion` varchar(50) NOT NULL,
    `fecha_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `id_convalidacion` (`id_convalidacion`),
    KEY `id_usuario` (`id_usuario`),
    CONSTRAINT `convalidaciones_auditoria_ibfk_1` FOREIGN KEY (`id_convalidacion`) REFERENCES `convalidacion` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE,
        CONSTRAINT `convalidaciones_auditoria_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `docente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE configuracion_matricula (
    id INT AUTO_INCREMENT PRIMARY KEY,
    periodo INT NOT NULL,
    creditos INT NOT NULL,
    fecha_inicio DATE NOT NULL,
    ultimo_dia_matricula DATE NOT NULL
);

ALTER TABLE
    `concepto_ingreso` CHANGE `monto` `monto` BLOB NOT NULL;

ALTER TABLE
    `detalle_ingresos` CHANGE `subtotal` `subtotal` BLOB NOT NULL;

ALTER TABLE
    `ingresos` CHANGE `monto_total` `monto_total` BLOB NOT NULL;

ALTER TABLE
    `egresos` CHANGE `monto_total` `monto_total` BLOB NOT NULL;

ALTER TABLE
    `criterio_evaluacion` CHANGE `calificacion` `calificacion` BLOB NOT NULL;

ALTER TABLE
    `efrst` CHANGE `calificacion` `calificacion` BLOB NOT NULL;