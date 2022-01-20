<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DetailKolesterol extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();

        $this->forge->addField([
            'id_detail_kolesterol' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_pemeriksaan' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'merokok' => [
                'type' => 'INT',
                'constraint' => '1',
                'null' => true,
                'comment' => '2 = Merokok, 1 = Sedang berusaha berhenti merokok, 0 = Tidak Merokok'
            ],
            'obat_hipertensi' => [
                'type' => 'INT',
                'constraint' => '1',
                'null' => true,
                'comment' => '1 = Ya, 0 = Tidak'
            ],
            'kadar_gula' => [
                'type' => 'INT',
                'constraint' => '1',
                'null' => true,
                'comment' => '0 = < 120, 1 = 120 - 150, 2 = sisanya'
            ],
            'tekanan_darah' => [
                'type' => 'INT',
                'constraint' => '1',
                'null' => true,
                'comment' => '0 = < 120/80, 1 = 120 - 139 / 80 - 89, 2 = sisanya'
            ],
            'kadar_kolesterol' => [
                'type' => 'INT',
                'constraint' => '1',
                'null' => true,
                'comment' => '0 = <200, 1 = 200-239, 2 = sisanya'
            ],
            'score_kolesterol' => [
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
        $this->forge->addKey('id_detail_kolesterol', true);
        $this->forge->addForeignKey('id_pemeriksaan', 'pemeriksaan', 'id_pemeriksaan');
        $this->forge->createTable('detail_kolesterol', true);

        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('detail_kolesterol', true);
    }
}
