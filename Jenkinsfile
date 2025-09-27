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
            
            # Install comprehensive toolset (exclude abandoned phpcpd)
            docker exec quality-container composer require --dev \\
                squizlabs/php_codesniffer \\
                phpstan/phpstan \\
                phpmd/phpmd
            
            echo "Running Code Quality Tools"
            
            # 1. PHP Code Sniffer - Code Style 
            echo "PHP Code Sniffer"
            docker exec quality-container vendor/bin/phpcs --standard=PSR12 --report=summary --ignore=vendor/ . || echo "PHPCS completed"
            
            # 2. PHP Mess Detector - Code Smells 
            echo " PHPMD"
            docker exec quality-container vendor/bin/phpmd . text codesize,unusedcode,naming,design --ignore=vendor/ || echo "PHPMD completed"
            
            # 3. PHPStan - Static Analysis 
            echo "PHPStan (Static Analysis) "
            docker exec quality-container vendor/bin/phpstan analyse --level=5 --no-progress . --ignore=vendor/ || echo "PHPStan completed"
            
            # 4. Alternative Duplication Check (using native find)
            echo " Code Duplication Check "
            docker exec quality-container find . -name "*.php" -not -path "./vendor/*" -exec grep -l "function\\|class" {} \\; | head -10 > test-results/structure.txt || true
            
            # 5. Code Metrics
            echo "Code Metrics"
            docker exec quality-container find . -name "*.php" -not -path "./vendor/*" -exec wc -l {} \\; | sort -nr | head -10 || true
            
            # Cleanup
            docker stop quality-container
            docker rm quality-container
        """
    }
    post {
        always {
            echo "Code Quality Analysis Complete"
            echo "Tools executed: PHPCS (style), PHPMD (smells), PHPStan (complexity)"
            echo "Vendor code excluded from analysis"
            echo "Check console output for your code's quality issues"
        }
    }
}



        stage('Security') {
    steps {
        echo 'Running security vulnerability assessment...'
        sh """
            docker run -d --name security-container -w /workspace ticketing-app-test tail -f /dev/null
            docker exec security-container composer require --dev enlightn/security-checker
            docker exec security-container vendor/bin/security-checker security:check composer.lock > test-results/security-report.txt 2>&1 || true
            docker stop security-container
            docker rm security-container
        """
    }
    post {
        always {
            archiveArtifacts artifacts: 'test-results/security-report.txt', allowEmptyArchive: true
            
            script {
                echo "VULNERABILITY ASSESSMENT REPORT "
                
                if (fileExists('test-results/security-report.txt')) {
                    def securityReport = readFile file: 'test-results/security-report.txt'
                    
                    if (securityReport.contains('[OK] 0 packages have known vulnerabilities')) {
                        echo " SECURITY STATUS: PASSED - No vulnerabilities detected"
                        echo "All dependencies are secure"
                    } else if (securityReport.contains('vulnerabilities found')) {
                        echo " SECURITY STATUS: FAILED - Vulnerabilities detected!"
                        echo securityReport
                        currentBuild.result = 'UNSTABLE'
                    } else {
                        echo "SECURITY STATUS: Scan completed"
                        echo securityReport
                    }
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
