pipeline {
    agent any

    environment {
        // Connect to the Docker-in-Docker container
        DOCKER_HOST = 'tcp://docker:2375'
        APP_NAME = 'ticketing-app'
        TEST_PORT = '8081'
    }

    stages {

        stage('Build') {
            steps {
                echo 'Building Docker image for Ticketing project...'
                sh """
                    # Build a Docker image from the repo
                    docker build -t $APP_NAME .
                """
            }
        }
stage('Test') {
    steps {
        sh 'pwd && ls -la'
        sh 'docker run --rm -v /var/jenkins_home/workspace/Ticketing-Pipeline:/workspace -w /workspace ticketing-app ls -la'
        sh 'docker run --rm -v /var/jenkins_home/workspace/Ticketing-Pipeline:/workspace -w /workspace ticketing-app sh -c "pwd && ls -la && which composer && which phpunit"'
    }
}
        
stage('Test') {
    steps {
        echo 'Running automated tests inside Docker...'

        // Run PHPUnit inside Docker and create test-results folder if it doesn't exist
        sh '''
            docker run --rm -v /var/jenkins_home/workspace/Ticketing-Pipeline:/workspace -w /workspace ticketing-app sh -c "mkdir -p test-results && composer install --no-interaction --prefer-dist && vendor/bin/phpunit --configuration phpunit.xml --log-junit test-results/junit.xml"  '''
    }

    post {
        always {
            // Publish JUnit test results to Jenkins
            junit 'test-results/junit.xml'
        }
    }
}


        stage('Code Quality') {
            steps {
                echo 'Running code quality analysis...'
                sh """
                    # Example: Using SonarScanner if installed in image or agent
                    if command -v sonar-scanner > /dev/null; then
                        sonar-scanner \
                            -Dsonar.projectKey=Ticketing \
                            -Dsonar.sources=. \
                            -Dsonar.host.url=http://your-sonarqube-server:9000 \
                            -Dsonar.login=YOUR_SONAR_TOKEN
                    else
                        echo 'SonarScanner not installed. Skipping code quality analysis.'
                    fi
                """
            }
        }

        stage('Deploy') {
            steps {
                echo 'Deploying Docker container to test environment...'
                sh """
                    # Stop any previous test container
                    docker rm -f ${APP_NAME}-test || true

                    # Run container on test port
                    docker run -d --name ${APP_NAME}-test -p $TEST_PORT:80 $APP_NAME
                """
                echo "Application deployed on port $TEST_PORT"
            }
        }
    }

    post {
        always {
            echo 'Cleaning up any dangling Docker containers...'
            sh 'docker container prune -f || true'
        }

        success {
            echo 'Pipeline completed successfully!'
        }

        failure {
            echo 'Pipeline failed. Check logs for errors.'
        }
    }
}
