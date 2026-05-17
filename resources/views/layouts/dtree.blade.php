<?php
// 在後端將資料分類：先撈出所有目錄（type=1），再撈出所有連結（type=2）
$folders_list = \App\Models\Tree::where('type', 1)->orderBy('order_by')->orderBy('name')->get();
$links_list   = \App\Models\Tree::where('type', 2)->orderBy('order_by')->orderBy('name')->get();
?>

{{-- 基礎安全 CSS，控制資料夾展開時的箭頭旋轉效果與間距 --}}
<style nonce="{{ $csp_nonce }}">
    .tree-toggle-btn[aria-expanded="true"] .fa-chevron-right {
        transform: rotate(90deg);
    }
    .tree-toggle-btn .fa-chevron-right {
        transition: transform 0.2s ease-in-out;
    }
    .tree-list-group-item {
        border: none;
        padding-left: 1.5rem;
    }
</style>

<div class="w-100">
    {{-- 1. 切換按鈕 --}}
    <div class="mb-2">
        <button type="button" id="btn-tree-toggle" class="btn btn-link btn-sm text-decoration-none p-0 text-secondary" data-status="closed">
            全部打開
        </button>
    </div>

    {{-- 2. 樹狀內容主體 (已移除原本的「連結收集」標題列) --}}
    <div class="ps-1">
        <ul class="list-group list-group-flush bg-transparent">
            
            {{-- 先渲染所有「子目錄」 --}}
            @foreach($folders_list as $folder)
                <li class="list-group-item border-0 px-0 bg-transparent py-1">
                    {{-- 點擊整條列，控制下方對應的 id 展開或收合 --}}
                    <div class="tree-toggle-btn d-flex align-items-center text-dark fw-bold" 
                         style="cursor: pointer;"
                         data-bs-toggle="collapse" 
                         data-bs-target="#folder-content-{{ $folder->id }}" 
                         aria-expanded="false">
                        <i class="fas fa-chevron-right text-muted btn-sm me-2" style="font-size: 0.8rem;"></i>
                        <i class="fas fa-folder text-warning me-2"></i>
                        {{ $folder->name }}
                    </div>

                    {{-- 該目錄底下的內容物容器 --}}
                    <div class="collapse tree-collapse ms-4 mt-1 ps-2 border-start" id="folder-content-{{ $folder->id }}">
                        <ul class="list-group list-group-flush bg-transparent">
                            <?php
                                // 撈出隸屬於此目錄下的所有內容
                                $sub_items = $links_list->where('folder_id', $folder->id);
                            ?>
                            @forelse($sub_items as $item)
                                <li class="list-group-item tree-list-group-item bg-transparent py-1 px-0">
                                    <i class="fas fa-link text-secondary me-2" style="font-size: 0.8rem;"></i>
                                    <a href="{{ $item->url }}" target="_blank" class="text-decoration-none">{{ $item->name }}</a>
                                </li>
                            @empty
                                <li class="list-group-item tree-list-group-item bg-transparent text-muted py-1 px-0">
                                    <small class="fst-italic">(此目錄目前無連結)</small>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </li>
            @endforeach

            {{-- 再渲染直接屬於根目錄的獨立「連結」 --}}
            <?php 
                $root_links = $links_list->whereIn('folder_id', [0, null]);
            ?>
            @foreach($root_links as $link)
                <li class="list-group-item border-0 px-0 py-1 bg-transparent">
                    <div class="ms-4">
                        <i class="fas fa-link text-secondary me-2" style="font-size: 0.8rem;"></i>
                        <a href="{{ $link->url }}" target="_blank" class="text-decoration-none">{{ $link->name }}</a>
                    </div>
                </li>
            @endforeach

        </ul>
    </div>
</div>

{{-- 安全 JavaScript 區塊 --}}
<script nonce="{{ $csp_nonce }}">
    document.addEventListener("DOMContentLoaded", function() {
        // A. 單個節點點擊時的 aria-expanded 狀態同步
        const toggles = document.querySelectorAll('.tree-toggle-btn');
        toggles.forEach(btn => {
            btn.addEventListener('click', function() {
                const isExpanded = this.getAttribute('aria-expanded') === 'true';
                this.setAttribute('aria-expanded', !isExpanded);
            });
        });

        // B. 單一按鈕切換全部打開/關閉邏輯
        const toggleBtn = document.getElementById('btn-tree-toggle');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                const currentStatus = this.getAttribute('data-status');
                const collapseElements = document.querySelectorAll('.tree-collapse');
                const titleButtons = document.querySelectorAll('.tree-toggle-btn');

                if (currentStatus === 'closed') {
                    collapseElements.forEach(el => {
                        const bsCollapse = bootstrap.Collapse.getOrCreateInstance(el);
                        bsCollapse.show();
                    });
                    titleButtons.forEach(btn => btn.setAttribute('aria-expanded', 'true'));
                    
                    this.textContent = '全部關閉';
                    this.setAttribute('data-status', 'opened');
                } else {
                    collapseElements.forEach(el => {
                        const bsCollapse = bootstrap.Collapse.getOrCreateInstance(el);
                        bsCollapse.hide();
                    });
                    titleButtons.forEach(btn => btn.setAttribute('aria-expanded', 'false'));
                    
                    this.textContent = '全部打開';
                    this.setAttribute('data-status', 'closed');
                }
            });
        }
    });
</script>