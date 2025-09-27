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
            docker run --rm -w /workspace ticketing-app sh -c "
                composer install --no-interaction --prefer-dist &&
                mkdir -p test-results &&
                vendor/bin/phpcs --standard=PSR12 --report=summary --ignore=vendor/ . || echo 'PHPCS done' &&
                vendor/bin/phpmd . text codesize,unusedcode,naming,design --ignore=vendor/ || echo 'PHPMD done' &&
                vendor/bin/phpstan analyse --level=5 --no-progress . --ignore=vendor/ || echo 'PHPStan done' &&
                find . -name '*.php' -not -path './vendor/*' -exec grep -l 'function\\|class' {} \\; | head -10 > test-results/structure.txt ||
                true
            "
        """
    }
    post {
        always {
            archiveArtifacts artifacts: 'test-results/**', allowEmptyArchive: true
            echo "Code Quality Analysis Complete"
        }
    }
}


        stage('Security') {
    steps {
        echo 'Running automated security vulnerability assessment...'
        sh """
            docker run --rm -w /workspace ticketing-app sh -c "
                composer install --no-interaction --prefer-dist &&
                mkdir -p test-results &&
                vendor/bin/security-checker security:check composer.lock --format=json > test-results/security-report.json || true
            "
        """
    }
    post {
        always {
            script {
                if (fileExists('test-results/security-report.json')) {
                    def report = readJSON file: 'test-results/security-report.json'
                    if (report.empty) {
                        echo "SECURITY STATUS: PASSED - No known vulnerabilities detected"
                    } else {
                        echo "SECURITY STATUS: FAILED - ${report.size()} vulnerabilities found"
                        report.each { vuln ->
                            echo "• [${vuln.severity}] ${vuln.package} - ${vuln.title} (CVE: ${vuln.cve})"
                        }
                        currentBuild.result = 'UNSTABLE'
                    }
                } else {
                    echo "Security report not generated"
                }
            }
            archiveArtifacts artifacts: 'test-results/security-report.json', allowEmptyArchive: true
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
