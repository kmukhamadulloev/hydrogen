# Hydrogen
**Hydrogen** is a Docker-based development environment designed to streamline the setup and management of PHP projects. It leverages Docker Compose to orchestrate services, ensuring a consistent and efficient development workflow.

## Features
* **Dockerized Environment**: Simplifies the setup process by containerizing the application and its dependencies.
* **PHP Support**: Tailored for PHP applications, facilitating rapid development and testing.
* **Modular Configuration**: Organized directory structure for easy management and scalability.

## Project Structure

```
hydrogen/
├── config/
│   └── php/
├── projects/
│   └── public/
├── volumes/
├── .env.example
├── .gitignore
└── docker-compose.yml
```

* `config/php/`: Contains PHP configuration files.
* `projects/`: Directory for your projects.
* `volumes/`: Placeholder for data volumes.
* `.env.example`: Sample environment variables file.
* `docker-compose.yml`: Defines and configures Docker services.
* `volumes/caddy/Caddyfile.example`: Sample caddy configuration file.

## Getting Started

### Prerequisites

* [Docker](https://www.docker.com/get-started) installed on your machine.
* [Docker Compose](https://docs.docker.com/compose/install/) installed.

### Installation

1. **Clone the Repository**
   ```bash
   git clone https://github.com/kmukhamadulloev/hydrogen.git
   cd hydrogen
   ```

2. **Configure Environment Variables**
   Copy the example environment file and modify it as needed:
   ```bash
   cp .env.example .env
   ```

3. **Configure Caddy File**
   Copy the example environment file and modify it as needed:
   ```bash
   cp Caddyfile.example Caddyfile
   ```

3. **Build and Start the Containers**
   ```bash
   docker-compose up --build
   ```
   This command will build the Docker images and start the services defined in `docker-compose.yml`.

## Usage
Once the containers are up and running, you can access your PHP application by navigating to `http://localhost` in your web browser. Place your PHP files in the `projects/` directory to serve them via the web server.

## Contributing
Contributions are welcome! Please fork the repository and submit a pull request for any enhancements or bug fixes.

## License
This project is open-source and available under the [MIT License].