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
            docker exec quality-container composer require --dev \
                squizlabs/php_codesniffer \
                phpstan/phpstan \
                phpmd/phpmd \
                sebastian/phpcpd
            
            # 1. CODE STYLE & STRUCTURE: PHP Code Sniffer
            echo "=== PHP Code Sniffer (Code Style & Structure) ==="
            docker exec quality-container vendor/bin/phpcs --standard=PSR12 . > test-results/phpcs.txt 2>&1 || true
            
            # 2. CODE SMELLS & DESIGN PATTERNS: PHP Mess Detector
            echo "=== PHPMD (Code Smells & Design Issues) ==="
            docker exec quality-container vendor/bin/phpmd . text codesize,unusedcode,naming,design > test-results/phpmd.txt 2>&1 || true
            
            # 3. STATIC ANALYSIS & COMPLEXITY: PHPStan
            echo "=== PHPStan (Static Analysis & Complexity) ==="
            docker exec quality-container vendor/bin/phpstan analyse --level=5 > test-results/phpstan.txt 2>&1 || true
            
            # 4. CODE DUPLICATION: PHP Copy/Paste Detector
            echo "=== PHPCPD (Code Duplication) ==="
            docker exec quality-container vendor/bin/phpcpd . > test-results/phpcpd.txt 2>&1 || true
            
            # 5. MAINTAINABILITY: Code Metrics
            echo "=== Code Metrics (Maintainability) ==="
            docker exec quality-container find . -name "*.php" -exec wc -l {} \\; | sort -nr > test-results/complexity.txt || true
            docker exec quality-container find . -name "*.php" | wc -l > test-results/file-count.txt || true
            
            # Cleanup
            docker stop quality-container
            docker rm quality-container
        """
    }
    post {
        always {
            // Archive all results for review
            archiveArtifacts artifacts: 'test-results/*.txt', allowEmptyArchive: true
            
            // Generate quality summary
            script {
                echo "=== CODE QUALITY ANALYSIS SUMMARY ==="
                echo "Toolset: PHPCS (style), PHPMD (smells), PHPStan (complexity), PHPCPD (duplication)"
                
                if (fileExists('test-results/phpcs.txt')) {
                    def phpcs = readFile file: 'test-results/phpcs.txt'
                    echo "Code Style: ${phpcs.split('\n').findAll { it.contains('ERROR') || it.contains('WARNING') }.size} issues"
                }
                
                if (fileExists('test-results/phpmd.txt')) {
                    def phpmd = readFile file: 'test-results/phpmd.txt'
                    echo "Code Smells: ${phpmd.split('\n').findAll { it.contains('/workspace/') }.size} violations"
                }
                
                if (fileExists('test-results/phpcpd.txt')) {
                    def phpcpd = readFile file: 'test-results/phpcpd.txt'
                    echo "Duplication: ${phpcpd.contains('Found') ? 'Potential duplicates detected' : 'No duplication found'}"
                }
                
                if (fileExists('test-results/complexity.txt')) {
                    def complexity = readFile file: 'test-results/complexity.txt'
                    def lines = complexity.split('\n').findAll { it.trim() }
                    echo "Largest file: ${lines[0]?.replace('/workspace/', '') ?: 'N/A'}"
                }
            }
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
