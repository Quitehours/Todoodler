<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%todo}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%group}}`
 */
class m210111_122406_create_todo_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%todo}}', [
            'id' => $this->primaryKey(),
            'text' => $this->text(),
            'user_id' => $this->integer()->notNull(),
            'group_id' => $this->integer(),
            'completed' => $this->boolean()->defaultValue(false),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-todo-user_id}}',
            '{{%todo}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-todo-user_id}}',
            '{{%todo}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `group_id`
        $this->createIndex(
            '{{%idx-todo-group_id}}',
            '{{%todo}}',
            'group_id'
        );

        // add foreign key for table `{{%group}}`
        $this->addForeignKey(
            '{{%fk-todo-group_id}}',
            '{{%todo}}',
            'group_id',
            '{{%group}}',
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
            '{{%fk-todo-user_id}}',
            '{{%todo}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-todo-user_id}}',
            '{{%todo}}'
        );

        // drops foreign key for table `{{%group}}`
        $this->dropForeignKey(
            '{{%fk-todo-group_id}}',
            '{{%todo}}'
        );

        // drops index for column `group_id`
        $this->dropIndex(
            '{{%idx-todo-group_id}}',
            '{{%todo}}'
        );

        $this->dropTable('{{%todo}}');
    }
}
