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
                'comment' => '1 = Perokok Aktif, 2 = Sedang berusaha berhenti merokok, 3 = Tidak Merokok'
            ],
            'obat_hipertensi' => [
                'type' => 'INT',
                'constraint' => '1',
                'null' => true,
                'comment' => '1 = Tidak, 2 = Ya'
            ],
            'kadar_gula' => [
                'type' => 'INT',
                'constraint' => '1',
                'null' => true,
                'comment' => '1 = < 120, 2 = 120 - 150, 3 = > 150,  4 = Tidak diketahui'
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
                'comment' => '1 = > 240, 2 = 200-239, 3 = < 200, 4 = Tidak diketahui'
            ],
            'kadar_hdl' => [
                'type' => 'INT',
                'constraint' => '1',
                'null' => true,
                'comment' => '1 = < 30, 2 = 30 - 50, 3 = > 50, 4 = Tidak diketahui'
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
