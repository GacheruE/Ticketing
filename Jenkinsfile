pipeline {
    agent any

    environment {
        DOCKER_IMAGE = 'php:8.2-cli'
    }

    stages {
        stage("Build") {
            steps {
                echo "Building project and creating build artefact"
                sh """
                    docker run --rm -v \$(pwd):/app -w /app \$DOCKER_IMAGE \
                    composer install --no-interaction
                """
            }
        }

        stage("Test") {
            steps {
                echo "Running unit and integration tests"
                sh """
                    docker run --rm -v \$(pwd):/app -w /app \$DOCKER_IMAGE \
                    vendor/bin/phpunit --testsuite unit
                """
            }
        }

        stage("Code Quality") {
            steps {
                echo "Running code quality analysis"
                sh """
                    docker run --rm -v \$(pwd):/app -w /app \$DOCKER_IMAGE \
                    ./vendor/bin/phpstan analyse src --level=max
                """
            }
        }

        stage("Security") {
            steps {
                echo "Running security scan"
                sh """
                    docker run --rm -v \$(pwd):/app -w /app \$DOCKER_IMAGE \
                    composer audit
                """
            }
        }

        stage("Deploy to Staging") {
            steps {
                echo "Deploying application to staging"
                // Example: run a Docker container as staging
                sh "docker build -t ticketing-app:staging ."
                sh "docker run -d --name ticketing-staging -p 8081:80 ticketing-app:staging"
            }
        }

        stage("Release to Production") {
            steps {
                echo "Promoting app to production"
                sh "docker build -t ticketing-app:latest ."
                sh "docker stop ticketing-prod || true"
                sh "docker rm ticketing-prod || true"
                sh "docker run -d --name ticketing-prod -p 8080:80 ticketing-app:latest"
            }
        }

        stage("Monitoring") {
            steps {
                echo "Monitoring production environment"
                // Example: simple check if prod container is running
                sh "docker ps | grep ticketing-prod || echo 'Production container not running!'"
            }
        }
    }

    post {
        always {
            echo "Cleaning up Docker containers"
            sh "docker container prune -f || true"
        }
        failure {
            echo "Pipeline failed. Check logs for errors."
        }
        success {
            echo "Pipeline finished successfully!"
        }
    }
}
