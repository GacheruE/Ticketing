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
        echo 'Running automated tests inside Docker...'
        sh """
            # Build image with current code
            docker build -t ticketing-app-test .
            
            # Run container and keep it running
            docker run -d --name test-container -w /workspace ticketing-app-test tail -f /dev/null
            
            # Execute tests and copy results
            docker exec test-container vendor/bin/phpunit --configuration phpunit.xml --log-junit test-results/junit.xml
            docker cp test-container:/workspace/test-results/junit.xml ./test-results/
            
            # Cleanup
            docker stop test-container
            docker rm test-container
        """
    }
    post {
        always {
            junit 'test-results/junit.xml'
        }
    }
}



        

        stage('Code Quality') {
    steps {
        echo 'Running PHP Code Sniffer for code quality analysis...'
        sh """
            # Run container for code quality analysis
            docker run -d --name quality-container -w /workspace ticketing-app-test tail -f /dev/null
            
            # Install PHP Code Sniffer
            docker exec quality-container composer require --dev squizlabs/php_codesniffer
            
            # Run code style analysis
            docker exec quality-container vendor/bin/phpcs --standard=PSR12 --report=checkstyle --report-file=test-results/checkstyle.xml . || true
            
            # Cleanup
            docker stop quality-container
            docker rm quality-container
        """
    }
    post {
        always {
            recordIssues tools: [phpCodeSniffer(pattern: 'test-results/checkstyle.xml')]
        }
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
