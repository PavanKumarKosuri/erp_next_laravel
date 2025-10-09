<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Bill of Materials
        Schema::create('bill_of_materials', function (Blueprint $table) {
            $table->id();
            $table->string('bom_number', 50)->unique();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('name', 100);
            $table->string('version', 20)->default('1.0');
            $table->decimal('quantity', 12, 2)->default(1);
            $table->decimal('unit_cost', 15, 2)->default(0);
            $table->decimal('total_cost', 15, 2)->default(0);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();

            $table->index('bom_number');
            $table->index(['product_id', 'is_active']);
        });

        // BOM Components
        Schema::create('bom_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bom_id')->constrained('bill_of_materials')->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity', 12, 2);
            $table->decimal('unit_cost', 15, 2);
            $table->decimal('total_cost', 15, 2);
            $table->decimal('scrap_percentage', 5, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['bom_id', 'product_id']);
        });

        // Work Orders
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->string('wo_number', 50)->unique();
            $table->foreignId('bom_id')->nullable()->constrained('bill_of_materials')->onDelete('set null');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('warehouse_id')->constrained()->onDelete('cascade');
            $table->decimal('planned_quantity', 12, 2);
            $table->decimal('produced_quantity', 12, 2)->default(0);
            $table->decimal('rejected_quantity', 12, 2)->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('expected_completion_date')->nullable();
            $table->date('actual_completion_date')->nullable();
            $table->enum('status', ['draft', 'pending', 'approved', 'in_progress', 'completed', 'cancelled'])->default('draft');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();

            $table->index('wo_number');
            $table->index('status');
            $table->index('priority');
            $table->index('expected_completion_date');
        });

        // Production Outputs
        Schema::create('production_outputs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->onDelete('cascade');
            $table->string('output_number', 50)->unique();
            $table->date('production_date');
            $table->decimal('quantity_produced', 12, 2);
            $table->decimal('quantity_rejected', 12, 2)->default(0);
            $table->string('batch_number', 50)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->index('output_number');
            $table->index('production_date');
            $table->index('batch_number');
        });

        // Quality Checks
        Schema::create('quality_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->onDelete('cascade');
            $table->string('check_number', 50)->unique();
            $table->date('check_date');
            $table->string('inspector', 100);
            $table->integer('sample_size')->default(0);
            $table->integer('passed_count')->default(0);
            $table->integer('failed_count')->default(0);
            $table->json('defect_types')->nullable();
            $table->enum('status', ['passed', 'failed', 'pending'])->default('pending');
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->index('check_number');
            $table->index('check_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quality_checks');
        Schema::dropIfExists('production_outputs');
        Schema::dropIfExists('work_orders');
        Schema::dropIfExists('bom_components');
        Schema::dropIfExists('bill_of_materials');
    }
};
