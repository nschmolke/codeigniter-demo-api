<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BlogPost extends Migration
{
	public function up()
	{
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'status'     => [
                'type' => 'ENUM("Draft", "Published")',
            ],
            'title'       => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
            'deleted_at datetime default NULL',

        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('blogposts');
	}

	public function down()
	{
        $this->forge->dropTable('blogposts');
	}
}
