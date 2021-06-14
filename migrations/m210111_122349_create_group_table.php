<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%group}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m210111_122349_create_group_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%group}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'name' => $this->string(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-group-user_id}}',
            '{{%group}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-group-user_id}}',
            '{{%group}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-group-user_id}}',
            '{{%group}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-group-user_id}}',
            '{{%group}}'
        );

        $this->dropTable('{{%group}}');
    }
}
