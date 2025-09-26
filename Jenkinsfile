pipeline {
    agent any

    environment {
        APP_DIR = "/var/jenkins_home/workspace/Ticketing-Pipeline"
    }

    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Build') {
            steps {
                echo "Building project and installing dependencies"
                sh """
                    docker run --rm -v $APP_DIR:/app -w /app php:8.2-cli composer install --no-interaction
                """
            }
        }

        stage('Test') {
            steps {
                echo "Running tests"
                sh """
                    docker run --rm -v $APP_DIR:/app -w /app php:8.2-cli php vendor/bin/phpunit
                """
            }
        }

        stage('Code Quality') {
            steps {
                echo "Checking code quality"
                sh """
                    docker run --rm -v $APP_DIR:/app -w /app php:8.2-cli php vendor/bin/phpstan analyse
                """
            }
        }

        stage('Deploy') {
            steps {
                echo "Deploy stage placeholder"
            }
        }
    }

    post {
        always {
            echo 'Cleaning up Docker containers'
            sh "docker container prune -f || true"
        }
        success {
            echo 'Pipeline completed successfully!'
        }
        failure {
            echo 'Pipeline failed. Check logs for errors.'
        }
    }
}
