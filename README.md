![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white)
![Apache](https://img.shields.io/badge/Apache-D22128?style=for-the-badge&logo=apache&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)

# Sistema de Turnos del Banco

Sistema completo de gestión de turnos para bancos que permite a los clientes solicitar turnos por zona y a los asesores atender de manera organizada. El sistema garantiza que cada asesor solo pueda atender a un cliente a la vez, optimizando el flujo de atención.

## Características Principales

### Gestión de Turnos
- **Solicitud de Turnos**: Los clientes pueden solicitar turnos en diferentes zonas
- **Asignación Inteligente**: Sistema automático de asignación de números de turno
- **Gestión por Zonas**: Cuatro zonas especializadas (A, B, C, D)
- **Control de Concurrencia**: Un asesor solo puede atender un cliente a la vez

### Zonas de Atención
- **Zona A**: Trámites Generales
- **Zona B**: Solicitar Documentos  
- **Zona C**: Transacciones en Caja
- **Zona D**: Asesorías Especializadas

### Panel de Asesor
- **Turnos en Espera**: Vista en tiempo real de turnos pendientes
- **Atención Activa**: Control del turno actual siendo atendido
- **Contador de Tiempo**: Registro automático del tiempo de atención
- **Finalización de Turnos**: Registro completo de duración y finalización

### Registro y Estadísticas
- **Historial Completo**: Registro de todas las asesorías realizadas
- **Tiempos de Atención**: Cálculo automático de duración por sesión
- **Búsquedas Avanzadas**: Consultas por asesor o cliente
- **Estados en Tiempo Real**: Seguimiento de turnos en curso y finalizados

## Arquitectura del Sistema

### Base de Datos
- **MySQL 5.7**: Motor de base de datos relacional
- **Tablas Principales**:
  - `Cliente`: Información de clientes
  - `Asesor`: Datos de los asesores
  - `Turno`: Gestión de turnos y estados
  - `Asesoria`: Registro de sesiones de atención

### Backend
- **PHP 7.4**: Lenguaje de programación del lado del servidor
- **Apache**: Servidor web con módulo PHP
- **Sesiones**: Gestión de estado de usuario y autenticación

### Frontend
- **Bootstrap 5**: Framework CSS para diseño responsivo
- **TailwindCSS**: Utilidades CSS adicionales
- **JavaScript**: Actualizaciones en tiempo real y contadores

## Requisitos

- Docker Desktop
- Docker Compose

## Instalación y Ejecución

### 1. Clonar el Repositorio
```bash
git clone https://github.com/stiiven19/turnos-banco.git
cd turnos-app
```

### 2. Iniciar los Contenedores
```bash
docker compose up --build
```

### 3. Acceder a la Aplicación
- **Aplicación**: http://localhost:80
- **Base de Datos**: localhost:3306
  - Usuario: `user`
  - Contraseña: `password`
  - Base de datos: `turnos-app`

### 4. Detener los Contenedores
```bash
docker compose down
```


## Flujo de Uso

### Para Clientes
1. Acceder a la página principal
2. Seleccionar la zona deseada (A, B, C o D)
3. Ingresar identificación y nombre
4. Recibir número de turno asignado

![Menu](images/Menu.png)

### Para Asesores
1. Iniciar sesión con credenciales de asesor
2. Ver turnos en espera para su zona
3. Seleccionar "Atender Cliente" (solo uno a la vez)
4. Monitorear tiempo de atención con contador automático
5. Finalizar turno cuando complete la atención

![Menu Turnos 1](images/Menu%20Turnos%201.png)

### Panel de Atención
El sistema muestra en tiempo real los turnos disponibles y permite al asesor gestionar las atenciones de manera controlada, garantizando que solo un cliente sea atendido simultáneamente.

![asesor con clientes](images/asesor%20con%20clientes.png)

### Control de Concurrencia
- Implementación de bloqueo para evitar atención múltiple
- Verificación en tiempo real del estado del asesor
- Mensajes informativos sobre disponibilidad

### Actualizaciones en Tiempo Real
- AJAX para actualizar lista de turnos cada 5 segundos
- Contador de tiempo de atención en vivo
- Estados sincronizados entre usuarios

### Seguridad
- Sesiones seguras para asesores
- Validación de datos de entrada
- Prevención de acceso no autorizado

## Configuración de Base de Datos

El sistema inicializa automáticamente las tablas necesarias:

## 👨‍💻 Desarrollado por

Steven Guerrero - Ingeniero de Sistemas  
