<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // The assessment/exam itself
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['code', 'english', 'iq']);
            $table->decimal('max_score', 5, 2)->default(100);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            $table->index('type');
        });

        // Questions inside each assessment
        Schema::create('assessment_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->cascadeOnDelete();
            $table->text('question_text');
            $table->enum('question_type', ['multiple_choice', 'fill_in', 'code']);
            $table->json('options')->nullable(); // MCQ choices ["A","B","C","D"]
            $table->text('correct_answer')->nullable(); // For reference / auto-grade
            $table->decimal('points', 5, 2)->default(1);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Student's overall submission/attempt for an assessment
        Schema::create('assessment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('score', 5, 2)->nullable();
            $table->enum('status', ['in_progress', 'submitted', 'graded'])->default('in_progress');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            $table->unique(['assessment_id', 'user_id']);
        });

        // Student's answer to each question
        Schema::create('assessment_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('assessment_submissions')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('assessment_questions')->cascadeOnDelete();
            $table->text('answer_text')->nullable();
            $table->decimal('points_earned', 5, 2)->nullable();
            $table->timestamps();
            $table->unique(['submission_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_answers');
        Schema::dropIfExists('assessment_submissions');
        Schema::dropIfExists('assessment_questions');
        Schema::dropIfExists('assessments');
    }
};