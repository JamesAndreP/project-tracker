<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ['Planning', 'In Progress', 'On Hold', 'Completed'];
        $priorities = ['Low', 'Medium', 'High'];
        $clients = [
            'Acme Corporation',
            'Tech Innovations Inc',
            'Global Solutions Ltd',
            'Digital Dynamics',
            'Future Systems',
            'Cloud Nine Technologies',
            'Peak Performance',
            'Elite Software',
            'Pinnacle Digital',
            'Summit Technologies',
            'Horizon Digital',
            'Vertex Solutions',
            'Apex Innovations',
            'Nexus Systems',
            'Prime Technologies',
            'Omni Digital',
            'Quantum Solutions',
            'Stellar Technologies',
            'Zenith Systems',
            'Pulse Digital',
            'Nova Tech Solutions',
        ];

        $projectNames = [
            'Website Redesign',
            'Mobile App Development',
            'E-commerce Platform',
            'CRM Integration',
            'Cloud Migration',
            'API Development',
            'Database Optimization',
            'Security Audit',
            'Performance Tuning',
            'UI/UX Design',
            'Content Management System',
            'Payment Gateway Integration',
            'Inventory Management',
            'Customer Portal',
            'Employee Dashboard',
            'Reporting System',
            'Data Analytics Platform',
            'Chatbot Development',
            'Machine Learning Model',
            'Blockchain Integration',
            'IoT Dashboard',
        ];

        $descriptions = [
            'Complete overhaul of existing website with modern design and improved user experience.',
            'Native mobile application for iOS and Android platforms with cross-platform compatibility.',
            'Full-featured e-commerce platform with payment integration and inventory management.',
            'Integration of CRM system with existing business processes and third-party applications.',
            'Migration of on-premise infrastructure to cloud-based solutions for better scalability.',
            'RESTful API development for integration with external services and mobile applications.',
            'Database performance optimization and query optimization for faster data retrieval.',
            'Comprehensive security audit to identify vulnerabilities and implement security best practices.',
            'Application performance tuning and optimization for improved response times.',
            'Modern UI/UX design with focus on user experience and accessibility compliance.',
            'Custom content management system for easy website content updates and management.',
            'Secure payment gateway integration with multiple payment providers and fraud detection.',
            'Inventory management system with real-time tracking and automated alerts.',
            'Customer self-service portal for account management and support ticket submission.',
            'Executive dashboard for KPI tracking and business intelligence reporting.',
            'Automated reporting system with custom report generation and scheduling.',
            'Data analytics platform with real-time insights and predictive analytics capabilities.',
            'AI-powered chatbot for customer support and automated query resolution.',
            'Machine learning model development for predictive analytics and automation.',
            'Blockchain-based solution for secure transaction processing and smart contracts.',
            'IoT dashboard for monitoring connected devices and real-time sensor data visualization.',
        ];

        for ($i = 0; $i < 20; $i++) {
            $startDate = now()->addDays(rand(1, 30));
            $dueDate = $startDate->copy()->addDays(rand(30, 90));

            Project::create([
                'client_name' => $clients[$i],
                'project_name' => $projectNames[$i],
                'description' => $descriptions[$i],
                'status' => $statuses[rand(0, 3)],
                'priority' => $priorities[rand(0, 2)],
                'start_date' => $startDate->format('Y-m-d'),
                'due_date' => $dueDate->format('Y-m-d'),
            ]);
        }

        $this->command->info('20 projects seeded successfully.');
    }
}
