pipeline {
    agent { label 'php-node' }

    environment {
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
            }
        }

        stage('Static Analysis / Security') {
            steps {
                sh "${PHPSTAN} analyse src --level=max || true"
            }
        }

        stage('Deploy to Staging') {
            steps {
                echo "Deploying to staging..."
            }
        }

        stage('Release to Production') {
            steps {
                echo "Releasing to production..."
            }
        }

        stage('Monitoring') {
            steps {
                echo "Monitoring setup..."
            }
        }
    }

    post {
        always {
            echo "Pipeline finished."
        }
    }
}
