<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PhoneBookSeeder extends Seeder
{
    public function run()
    {
        // 1. 引入 Faker 库生成假数据
        $faker = \Faker\Factory::create();

        // 2. 先创建一个测试用户 (满足 contacts 表的外键 user_id 需求)
        $testUser = [
            'username'   => 'testadmin',
            'password'   => password_hash('password123', PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        
        // 插入用户并获取新用户的 ID
        $this->db->table('users')->insert($testUser);
        $userId = $this->db->insertID();

        // 3. 准备批量插入 20 个联系人
        $contactsData = [];
        
        for ($i = 0; $i < 20; $i++) {
            $contactsData[] = [
                'user_id'    => $userId,
                'name'       => $faker->name,                  // 随机生成真实姓名
                'phone'      => $faker->phoneNumber,           // 随机生成电话号码
                'email'      => $faker->unique()->safeEmail,   // 随机生成唯一邮箱
                'image_path' => 'default.png',                 // 默认头像
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        // 4. 使用 insertBatch 批量插入（性能远高于在循环里一条条 insert）
        $this->db->table('contacts')->insertBatch($contactsData);
        
        // 在终端输出提示
        echo "Successfully seeded 1 test user and 20 fake contacts! \n";
    }
}