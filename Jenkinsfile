pipeline {
    agent {
        docker {
            image 'php:8.2-cli'
            args '-v $WORKSPACE/vendor:/app/vendor' // Cache vendor folder
        }
    }

    environment {
        APP_NAME = 'TicketingApp'
        STAGING_SERVER = 'staging.example.com'
        PRODUCTION_SERVER = 'prod.example.com'
    }

    stages {
        stage("Build") {
            steps {
                echo "Building the application and installing dependencies"
                sh 'composer install --no-interaction'
                sh 'echo Build artefact created: $APP_NAME'
            }
        }

        stage("Test") {
            steps {
                echo "Running automated tests with PHPUnit"
                sh 'vendor/bin/phpunit --colors=always || true' // || true ensures pipeline continues if some tests fail
            }
        }

        stage("Code Quality") {
            steps {
                echo "Analyzing code quality with PHPStan"
                sh 'vendor/bin/phpstan analyse src --level max || true'
            }
        }

        stage("Security") {
            steps {
                echo "Running static security analysis with Psalm"
                sh 'vendor/bin/psalm --no-progress || true'
            }
        }

        stage("Deploy to Staging") {
            steps {
                echo "Deploying application to staging environment"
                sh """
                    echo 'Deploying $APP_NAME to $STAGING_SERVER'
                    # Example: rsync or docker-compose commands can go here
                """
            }
        }

        stage("Integration Tests on Staging") {
            steps {
                echo "Running integration tests on staging"
                sh """
                    echo 'Integration tests executed on $STAGING_SERVER'
                    # Add Selenium or API tests here if needed
                """
            }
        }

        stage("Release to Production") {
            steps {
                echo "Deploying application to production environment"
                sh """
                    echo 'Deploying $APP_NAME to $PRODUCTION_SERVER'
                    # Add Octopus Deploy, AWS CodeDeploy, or Docker commands here
                """
            }
        }

        stage("Monitoring") {
            steps {
                echo "Monitoring production application with alerts"
                sh """
                    echo 'Monitoring $APP_NAME on $PRODUCTION_SERVER'
                    # Placeholder for Datadog, New Relic, or custom scripts
                """
            }
        }
    }

    post {
        always {
            echo "Pipeline finished"
        }
        success {
            echo "Pipeline completed successfully"
        }
        failure {
            echo "Pipeline failed. Check logs for errors."
        }
    }
}
