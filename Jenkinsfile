pipeline {
    agent {
        docker {
            image 'php:8.2-cli'
            args '-w /workspace -v /var/jenkins_home/workspace/Ticketing-Pipeline:/workspace'
        }
    }
    
    stages {
        stage('Test Docker Agent') {
            steps {
                sh '''
                    echo "PHP is working!"
                    php --version
                    echo " Composer is available!"
                    composer --version
                    echo " Current directory:"
                    pwd
                    ls -la
                '''
            }
        }
    }
}
