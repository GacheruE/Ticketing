pipeline {
    agent any

    environment {
        PHP_BIN = 'php'
        COMPOSER = 'composer'
        PHPUNIT = './vendor/bin/phpunit'
        PHPCS = './vendor/bin/phpcs'
        PHPSTAN = './vendor/bin/phpstan'
    }

    stages {

        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Install Dependencies') {
            steps {
                sh "${COMPOSER} install --no-interaction"
            }
        }

        stage('Run Tests') {
            steps {
                sh "${PHPUNIT} tests"
            }
        }

        stage('Code Quality') {
            steps {
                sh "${PHPCS} --standard=PSR12 . || true"
                // true ensures Jenkins continues even if coding standards warnings exist
            }
        }

        stage('Static Analysis / Security') {
            steps {
                sh "${PHPSTAN} analyse src --level=max || true"
                // true ensures Jenkins continues even if issues are found
            }
        }

        stage('Deploy to Staging') {
            steps {
                echo "Deploying to staging..."
                // Add your staging deployment commands here
            }
        }

        stage('Release to Production') {
            steps {
                echo "Releasing to production..."
                // Add your production deployment commands here
            }
        }

        stage('Monitoring') {
            steps {
                echo "Monitoring setup..."
                // Add monitoring/alerting commands or scripts here
            }
        }
    }

    post {
        always {
            echo "Pipeline finished."
        }
    }
}
