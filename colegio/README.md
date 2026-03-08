# 🎓 Colegio San Andrés – Sistema de Registro Estudiantil

Proyecto web completo con **HTML + CSS + PHP + MySQL + Java (Servlet)**.

---

## 📁 Estructura del Proyecto

```
colegio/
├── index.html            ← Página de inicio
├── registro.html         ← Formulario de registro
├── lista.php             ← Ver todos los estudiantes
├── editar.php            ← Editar un estudiante
├── css/
│   └── style.css         ← Estilos del sistema
├── php/
│   ├── conexion.php      ← Conexión a MySQL
│   ├── guardar.php       ← Insertar estudiante
│   ├── actualizar.php    ← Actualizar estudiante
│   └── eliminar.php      ← Eliminar estudiante
├── java/
│   └── src/
│       └── EstudiantesServlet.java   ← API REST en Java
└── base_de_datos.sql     ← Script para crear la BD
```

---

## ⚙️ Instalación Paso a Paso

### 1. Base de datos MySQL

1. Abre **phpMyAdmin** (o MySQL Workbench)
2. Importa o ejecuta el archivo `base_de_datos.sql`
3. Esto crea la base de datos `colegio_db` con la tabla y datos de prueba

### 2. Configurar la conexión PHP

Edita el archivo `php/conexion.php`:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'tu_usuario');   // ← Cambia esto
define('DB_PASS', 'tu_contraseña'); // ← Cambia esto
define('DB_NAME', 'colegio_db');
```

### 3. Servidor PHP (XAMPP / WAMP / Laragon)

1. Instala **XAMPP** desde https://www.apachefriends.org
2. Copia la carpeta `colegio/` a `C:\xampp\htdocs\`
3. Inicia Apache y MySQL desde el panel de XAMPP
4. Abre en el navegador: **http://localhost/colegio/**

### 4. Servlet Java (opcional – API REST)

Requisitos: **Tomcat 10+**, **JDK 17+**

1. Descarga las librerías:
   - `mysql-connector-j-8.x.x.jar`
   - `gson-2.x.x.jar`
   - `jakarta.servlet-api-6.x.jar`

2. Compila el servlet:
   ```bash
   javac -cp ".;lib/*" -d out/ java/src/EstudiantesServlet.java
   ```

3. Despliega en Tomcat y accede a:
   - `http://localhost:8080/colegio/api/estudiantes`        → todos
   - `http://localhost:8080/colegio/api/estudiantes?id=1`   → uno
   - `http://localhost:8080/colegio/api/estudiantes?grado=8`→ por grado

---

## 🌐 Páginas del Sistema

| Página           | URL                                | Descripción                     |
|------------------|------------------------------------|---------------------------------|
| Inicio           | `index.html`                       | Página principal                |
| Registro         | `registro.html`                    | Registrar nuevo estudiante      |
| Lista            | `lista.php`                        | Ver, buscar todos los estudiantes|
| Editar/Eliminar  | `editar.php?id=X` / botón eliminar | Gestión de registros            |
| API REST (Java)  | `/api/estudiantes`                 | JSON para consumo externo       |

---

## 🛢️ Tabla MySQL – `estudiantes`

| Campo          | Tipo                          | Descripción              |
|----------------|-------------------------------|--------------------------|
| id             | INT AUTO_INCREMENT            | Clave primaria           |
| nombres        | VARCHAR(100)                  | Nombres del estudiante   |
| apellidos      | VARCHAR(100)                  | Apellidos                |
| documento      | VARCHAR(20) UNIQUE            | N° de documento          |
| fecha_nac      | DATE                          | Fecha de nacimiento      |
| genero         | CHAR(1)                       | M / F / O                |
| telefono       | VARCHAR(20)                   | Teléfono (opcional)      |
| grado          | TINYINT                       | 1 al 11                  |
| grupo          | CHAR(1)                       | A, B o C                 |
| año_matricula  | YEAR                          | Año de matrícula         |
| estado         | ENUM(activo,inactivo,retirado)| Estado del estudiante    |
| acudiente      | VARCHAR(100)                  | Nombre del acudiente     |
| tel_acudiente  | VARCHAR(20)                   | Teléfono del acudiente   |
| direccion      | VARCHAR(200)                  | Dirección (opcional)     |
| created_at     | TIMESTAMP                     | Fecha de registro        |
| updated_at     | TIMESTAMP                     | Última actualización     |

---

## ✅ Tecnologías Utilizadas

- **Frontend**: HTML5 + CSS3 (Grid, Flexbox, animaciones)
- **Backend PHP**: PHP 8+ con MySQLi preparado (anti SQL Injection)
- **Base de datos**: MySQL 8
- **Backend Java**: Jakarta Servlet 6 (API REST JSON con Gson)
- **Servidor**: XAMPP / Tomcat 10+

---

Desarrollado para Colegio San Andrés © 2025
