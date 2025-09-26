pipeline {
    agent {
        docker {
            image 'php:8.2-cli' // PHP CLI image
            args '-v $HOME/.composer:/root/.composer' // optional: persist Composer cache
        }
    }

    environment {
        COMPOSER_ALLOW_SUPERUSER = '1' // allows running Composer as root inside Docker
    }

    stages {
        stage('Checkout SCM') {
            steps {
                checkout scm
            }
        }

        stage('Install Dependencies') {
            steps {
                sh 'php -v' // confirm PHP version
                sh 'composer install --no-interaction'
            }
        }

        stage('Run Tests') {
            steps {
                // Adjust the test command depending on your framework
                sh './vendor/bin/phpunit --colors=always'
            }
        }

        stage('Code Quality') {
            steps {
                // Example using PHPStan (make sure it's in composer require-dev)
                sh './vendor/bin/phpstan analyse src --level max'
            }
        }

        stage('Static Analysis / Security') {
            steps {
                // Example using Psalm (if installed) or other tools
                sh './vendor/bin/psalm'
            }
        }

        stage('Deploy to Staging') {
            steps {
                echo 'Deploy to staging placeholder'
                // Add deployment scripts if needed
            }
        }

        stage('Release to Production') {
            steps {
                echo 'Release to production placeholder'
                // Add production deployment scripts if needed
            }
        }

        stage('Monitoring') {
            steps {
                echo 'Monitoring stage placeholder'
                // Add monitoring scripts if needed
            }
        }
    }

    post {
        always {
            echo 'Pipeline finished.'
        }
        success {
            echo 'Pipeline succeeded!'
        }
        failure {
            echo 'Pipeline failed.'
        }
    }
}
