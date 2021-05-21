<?php

use yii\db\Migration;

/**
 * Class m210518_131249_pretty_settings_table
 */
class m210518_131249_pretty_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%settings}}', [
            'id' => $this->string()->unique(),
            'value' => $this->string()->notNull(),
        ]);

        $this->createIndex('settings-key', '{{%settings}}', 'id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%settings}}');
    }

}
