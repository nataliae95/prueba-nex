# Mini CRM - Laravel + Vue 3 
Este proyecto es un Mini CRM para manejo de clientes, desarrollado como prueba técnica para Grupo Nex, usando:
- Laravel 11
- Vue.js 3
- MYSQL 8
- Docker
- Vite


## Requisitos previos
- Git
- Docker
- Docker Compose

## Instalación y despliegue
### Clonar proyecto
- `git clone <repositorio>`
- `cd nombre_proyecto`

### Crear archivo .env
`cp .env.example .env`
###Levantar contenedores
`docker compose up -d --build`

###Instalar dependencias
`docker exec -it agencia_php composer install`

###Ejecutar migraciones y seeders
`docker exec -it agencia_php php artisan migrate:fresh --seed`

###Levantar frontend
`docker exec -it agencia_php npm run dev -- --host`

------------



## Accesos del proyecto
### Aplicación
[http://localhost:8000](http://localhost:8000)

### phpMyAdmin
[http://localhost:8081](http://localhost:8081)

Credenciales (según .env)
- Usuario: root
- Password: 12345
- Base de datos: agencia

------------


## ## Modelo de datos

![](file:///Users/admin/Downloads/Prueba_Tecnica_Laravel_Vue.pdf)
