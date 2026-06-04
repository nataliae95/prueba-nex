<template>
    <nav v-if="meta.last_page > 1" class="mt-4" aria-label="Navegación">
        <ul class="pagination pagination-sm justify-content-center gap-1">
            <li class="page-item" :class="{ disabled: !meta.prev_page_url }">
                <button class="page-link" @click="$emit('change', meta.current_page - 1)">
                    <i class="bi bi-chevron-left"></i> Anterior
                </button>
            </li>
            <li class="page-item disabled">
                <span class="page-link">Pág. {{ meta.current_page }} de {{ meta.last_page }}</span>
            </li>
            <li class="page-item" :class="{ disabled: !meta.next_page_url }">
                <button class="page-link" @click="$emit('change', meta.current_page + 1)">
                    Siguiente <i class="bi bi-chevron-right"></i>
                </button>
            </li>
        </ul>
    </nav>
</template>

<script setup>
    defineProps(['meta']);
    defineEmits(['change']);
</script>