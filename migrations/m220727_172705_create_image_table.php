<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%image}}`.
 */
class m220727_172705_create_image_table extends Migration
{
    /** @var string */
    private string $table = '{{%image}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        /** @var string */
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->table, [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'old_name' => $this->string()->notNull(),
            'ext' => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('image_old_name_key', $this->table, 'old_name');
        $this->createIndex('image_ext_key', $this->table, 'ext');
        $this->createIndex('image_created_at_key', $this->table, 'created_at');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->table);
    }
}
