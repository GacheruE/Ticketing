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
        echo 'Running comprehensive code quality analysis...'
        sh """
            # Run container for code quality analysis
            docker run -d --name quality-container -w /workspace ticketing-app-test tail -f /dev/null
            
            # Install comprehensive toolset
            docker exec quality-container composer require --dev \\
                squizlabs/php_codesniffer \\
                phpstan/phpstan \\
                phpmd/phpmd \\
                sebastian/phpcpd
            
            # Run all quality tools
            echo "Running Code Quality Tools "
            
            # PHP Code Sniffer - Code Style
            docker exec quality-container vendor/bin/phpcs --standard=PSR12 --report=summary . || echo "PHPCS completed"
            
            # PHP Mess Detector - Code Smells
            docker exec quality-container vendor/bin/phpmd . text codesize,unusedcode,naming,design || echo "PHPMD completed"
            
            # PHPStan - Static Analysis  
            docker exec quality-container vendor/bin/phpstan analyse --level=5 --no-progress || echo "PHPStan completed"
            
            # PHPCPD - Duplication
            docker exec quality-container vendor/bin/phpcpd . || echo "PHPCPD completed"
            
            # Cleanup
            docker stop quality-container
            docker rm quality-container
        """
    }
    post {
        always {
            echo "Code Quality Analysis Complete"
            echo "Tools executed: PHPCS, PHPMD, PHPStan, PHPCPD"
            echo "Check console output for detailed results"
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
