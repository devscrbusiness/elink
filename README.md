# eLink - Plataforma de Perfiles de Empresas

eLink es una plataforma web diseñada para que las empresas puedan crear y gestionar fácilmente su presencia online a través de perfiles públicos personalizables. Permite a los empresas centralizar su información clave, enlaces a redes sociales y detalles de ubicación en una página.

## Características Principales

-   **Gestión de Información de la Empresa:** Las empresas pueden actualizar su nombre, descripción, sitio web y logo.
-   **Gestión de Enlaces Sociales:** Añade, edita y elimina enlaces de perfiles de redes sociales (WhatsApp, Telegram, Instagram, Facebook, X, TikTok, LinkedIn, etc.) y otros sitios web personalizados.
-   **Gestión de Ubicación:** Define las coordenadas de latitud y longitud, así como los detalles de la dirección de la empresa. La plataforma está preparada para integrar la selección de ubicación en un mapa.
-   **Perfiles Públicos Personalizables:** Cada empresa obtiene una URL personalizada para su página pública, lo que facilita compartir su información.

## Dependencias Clave

Este proyecto está construido sobre PHP y JavaScript, utilizando las siguientes tecnologías principales:

-   **Laravel:** framework PHP.
-   **Livewire:** Un framework que permite construir interfaces dinámicas usando solo PHP.
-   **Tailwind CSS:** Un framework de clases CSS.
-   **PHP:** Requiere PHP 8.x o superior.
-   **Composer:** El gestor de dependencias para PHP.
-   **Node.js & npm:** Para la gestión de activos frontend, compilación de CSS (incluyendo Autoprefixer) y JavaScript. Requiere Node.js 20.19+ y 22.12+.
-   **Base de Datos:** Compatible con bases de datos relacionales como MySQL, PostgreSQL o SQLite.

## Instalación (Ejemplo Básico de local)

Para poner en marcha el proyecto localmente, sigue estos pasos:

1.  **Clonar el repositorio:**
    ```bash
    git clone https://github.com/tu-usuario/elink.git
    cd elink
    ```

2.  **Instalar dependencias de PHP:**
    ```bash
    composer install
    ```

3.  **Instalar dependencias de JavaScript y compilar assets:**
    ```bash
    npm install
    npm run dev # o npm run build para producción
    ```

4.  **Configurar el entorno:**
    -   Copia el archivo `.env.example` a `.env`: `cp .env.example .env`
    -   Genera la clave de aplicación: `php artisan key:generate`
    -   Configura tu base de datos en el archivo `.env`.

5.  **Ejecutar migraciones de base de datos:**
    ```bash
    php artisan migrate
    ```

6.  **Crear un enlace simbólico (el symlink) para el almacenamiento de archivos (ej. logos):**
    ```bash
    php artisan storage:link
    ```

7.  **Iniciar el servidor de desarrollo:**
    ```bash
    php artisan serve
    ```



---