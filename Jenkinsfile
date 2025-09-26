pipeline {
    agent any

    environment {
        APP_DIR = '/var/jenkins_home/workspace/Ticketing-Pipeline'
    }

    stages {
        stage('Checkout') {
            steps {
                echo "Checking out source code"
                checkout scm
            }
        }

        stage('Build') {
            steps {
                echo "Building project and installing dependencies"
                // Run Docker commands as root
                sh 'docker run --rm -v $APP_DIR:/app -w /app php:8.2-cli composer install --no-interaction'
            }
        }

        stage('Test') {
            steps {
                echo "Running tests"
                // Example: run tests inside a Docker container
                sh 'docker run --rm -v $APP_DIR:/app -w /app php:8.2-cli php vendor/bin/phpunit'
            }
        }

        stage('Code Quality') {
            steps {
                echo "Running static analysis / code quality tools"
                sh 'docker run --rm -v $APP_DIR:/app -w /app php:8.2-cli php vendor/bin/phpstan analyse'
            }
        }

        stage('Deploy') {
            steps {
                echo "Deploy stage (example)"
                // Replace with your deploy commands
                sh 'echo "Deploying application..."'
            }
        }
    }

    post {
        always {
            echo "Cleaning up Docker containers"
            sh 'docker container prune -f || true'
        }
        success {
            echo "Pipeline succeeded!"
        }
        failure {
            echo "Pipeline failed. Check logs for errors."
        }
    }
}
