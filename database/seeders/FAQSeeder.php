<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FAQ;

class FAQSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'How does the investment platform work?',
                'answer' => 'Our platform allows you to invest in various plans and earn daily profits. Simply choose a plan that suits your investment goals, make a deposit, and start earning returns. Each plan has different investment amounts, daily profit rates, and durations.',
                'category' => 'General',
                'order' => 1,
                'status' => 'active'
            ],
            [
                'question' => 'What are the minimum and maximum withdrawal amounts?',
                'answer' => 'The minimum withdrawal amount is Rs. 500, and the maximum withdrawal amount is Rs. 50,000 per transaction. You can make multiple withdrawal requests as long as you have sufficient balance in your wallet.',
                'category' => 'Withdrawals',
                'order' => 2,
                'status' => 'active'
            ],
            [
                'question' => 'How long does it take to process withdrawals?',
                'answer' => 'Withdrawal requests are typically processed within 24-48 hours during business days. Once approved, the funds will be transferred to your registered bank account or payment method. You will receive a notification when your withdrawal is processed.',
                'category' => 'Withdrawals',
                'order' => 3,
                'status' => 'active'
            ],
            [
                'question' => 'Can I invest in multiple plans at the same time?',
                'answer' => 'Yes, you can invest in multiple plans simultaneously. Each plan is independent, and you can have active subscriptions to different plans based on your available balance and investment preferences.',
                'category' => 'Investments',
                'order' => 4,
                'status' => 'active'
            ],
            [
                'question' => 'What happens when my investment plan expires?',
                'answer' => 'When your investment plan expires, you can either reinvest in a new plan or withdraw your earnings. The platform will notify you before your plan expires, and you can choose to renew or withdraw your profits.',
                'category' => 'Investments',
                'order' => 5,
                'status' => 'active'
            ],
            [
                'question' => 'How do I add money to my wallet?',
                'answer' => 'To add money to your wallet, go to the deposit section and choose your preferred payment method. You can deposit funds using bank transfer, Easypaisa, or other supported payment methods. Once the payment is confirmed, the amount will be added to your wallet balance.',
                'category' => 'Deposits',
                'order' => 6,
                'status' => 'active'
            ],
            [
                'question' => 'Is my investment safe and secure?',
                'answer' => 'Yes, we prioritize the security of your investments. Our platform uses advanced security measures to protect your funds and personal information. All transactions are encrypted and monitored for any suspicious activity.',
                'category' => 'Security',
                'order' => 7,
                'status' => 'active'
            ],
            [
                'question' => 'What payment methods are accepted for deposits?',
                'answer' => 'We accept various payment methods including bank transfers, Easypaisa, JazzCash, and other digital payment solutions. You can choose the most convenient method for your deposits.',
                'category' => 'Deposits',
                'order' => 8,
                'status' => 'active'
            ],
            [
                'question' => 'How do I watch videos to earn additional income?',
                'answer' => 'After subscribing to an investment plan, you can access the video section to watch educational and promotional videos. Each video watched earns you additional income that gets added to your wallet balance.',
                'category' => 'Earnings',
                'order' => 9,
                'status' => 'active'
            ],
            [
                'question' => 'What should I do if I forget my password?',
                'answer' => 'If you forget your password, you can use the "Forgot Password" feature on the login page. Enter your registered email address, and we will send you a password reset link to create a new password.',
                'category' => 'Account',
                'order' => 10,
                'status' => 'active'
            ]
        ];

        foreach ($faqs as $faq) {
            FAQ::create($faq);
        }
    }
}
