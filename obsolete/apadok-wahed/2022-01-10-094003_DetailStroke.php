<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DetailStroke extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();

        $this->forge->addField([
            'id_detail_stroke' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_pemeriksaan' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            // 'umur' => [
            //     'type' => 'INT',
            //     'null' => true,
            // ],
            // 'berat_badan' => [
            //     'type' => 'DECIMAL',
            //     'constraint' => '10,3',
            //     'null' => true,
            // ],
            // 'tinggi_badan' => [
            //     'type' => 'DECIMAL',
            //     'constraint' => '10,3',
            //     'null' => true,
            // ],
            'bmi' => [
                'type' => 'DECIMAL',
                'constraint' => '10,3',
                'null' => true,
            ],
            'aktivitas_fisik' => [
                'type' => 'INT',
                'constraint' => '1',
                'null' => true,
                'comment' => '1 = Ya, 2 = Tidak, 3 = Jarang'
            ],
            'merokok' => [
                'type' => 'INT',
                'constraint' => '1',
                'null' => true,
                'comment' => '1 = Perokok Aktif, 2 = Sedang berusaha berhenti merokok, 3 = Tidak Merokok'
            ],
            'tekanan_darah' => [
                'type' => 'INT',
                'constraint' => '1',
                'null' => true,
                'comment' => '1 = > 140/90, 2 = 120 - 139 / 80 - 89, 3 = < 120/80, 4 = Tidak diketahui'
            ],
            'kadar_kolesterol' => [
                'type' => 'INT',
                'constraint' => '1',
                'null' => true,
                'comment' => '1 > 240, 2 = 200-239, 3 = < 200, 4 = Tidak diketahui'
            ],
            'riwayat_stroke' => [
                'type' => 'INT',
                'constraint' => '1',
                'null' => true,
                'comment' => '1 = Ya, 2 = Tidak , 3 = Tidak diketahui'
            ],
            'irama_jantung' => [
                'type' => 'INT',
                'constraint' => '1',
                'null' => true,
                'comment' => '1 = Ya, 2 = Tidak diketahui, 3 = Tidak pernah'
            ],
            // 'riwayat_stroke' => [
            //     'type' => 'INT',
            //     'constraint' => '1',
            //     'null' => true,
            //     'comment' => '0 = tidak, 1 = tidak diketahui, 2 = iya'
            // ],
            'kadar_gula' => [
                'type' => 'INT',
                'constraint' => '1',
                'null' => true,
                'comment' => '1 = < 120, 2 = 120 - 150, 3 = > 150,  4 = Tidak diketahui'
            ],
            'high_score' => [
                'type' => 'INT',
                'constraint' => '2',
                'null' => true,
                'default' => '0',
            ],
            'medium_score' => [
                'type' => 'INT',
                'constraint' => '2',
                'null' => true,
                'default' => '0',
            ],
            'low_score' => [
                'type' => 'INT',
                'constraint' => '2',
                'null' => true,
                'default' => '0',
            ],
            'updated_at' => [
                'type' => 'datetime',
                'null' => true,
            ],
            'created_at datetime default current_timestamp',
        ]);
        $this->forge->addKey('id_detail_stroke', true);
        $this->forge->addForeignKey('id_pemeriksaan', 'pemeriksaan', 'id_pemeriksaan');
        $this->forge->createTable('detail_stroke', true);

        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('detail_stroke', true);
    }
}
