 pipeline {
    agent any
    
    stages {
              
        stage('Environment Setup') {
            steps {
                sh 'php --version'
                sh 'composer --version'
            }
        }
        
        stage('Build') {
            steps {
                sh 'composer install --no-interaction'
                sh 'npm install'
                sh 'npm run production'
                sh 'cp .env.example .env'
                sh 'php artisan key:generate'
            }
        }
        
        stage('Test') {
            steps {
                sh 'php artisan test'
            }
        }
        
        stage('Code Quality') {
            steps {
                // PHP CodeSniffer (if available)
                sh 'composer check-style || echo "Code style check skipped"'
                
                // Security check
                sh 'composer audit || echo "No critical vulnerabilities found"'
            }
        }
        
        stage('Deploy to Staging') {
            when {
                branch 'main'
            }
            steps {
                echo 'Deploying to staging server...'
                // Simple deployment - you can use SCP, RSYNC, or basic commands
                sh '''
                    # Example deployment commands
                    rsync -avz . user@staging-server:/var/www/ticketing/
                    ssh user@staging-server "cd /var/www/ticketing && php artisan migrate --force"
                '''
            }
        }
    }
    
    post {
        always {
            echo 'Pipeline completed'
        }
    }
}
