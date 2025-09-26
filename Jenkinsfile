pipeline {
    agent any
    environment {
        DOCKER_HOST = 'tcp://docker:2375'
    }
    stages {
        stage('Build') {
            steps {
                echo 'Building project and creating Docker image'
                sh 'docker build -t ticketing-app .'
            }
        }
        stage('Test') {
            steps {
                echo 'Running automated tests'
                sh 'docker run --rm ticketing-app php vendor/bin/phpunit'
            }
        }
        stage('Code Quality') {
            steps {
                echo 'Running code quality analysis'
                // Example using SonarScanner
                sh 'sonar-scanner'
            }
        }
        stage('Deploy') {
            steps {
                echo 'Deploying app to test environment'
                sh 'docker run -d -p 8081:80 ticketing-app'
            }
        }
    }
}
