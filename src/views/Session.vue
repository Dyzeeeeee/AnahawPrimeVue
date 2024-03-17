<script setup>
import { onMounted, reactive, ref, watch } from 'vue';

</script>

<template>

    <div class="flex flex-row gap-2 relative">

        <Fieldset class="flex-auto flex-row" unstyled style="height: 80vh;">
            <template #legend>
                <div class="flex align-items-center pl-2">
                    <i class="pi pi-cart-plus mr-2"> </i>
                    <span class="font-bold text-xl">Session</span>
                </div>
            </template>
            <div class="absolute top-0 right-0 p-4 ">
                <Button icon="pi pi-plus" label="New Session">
                </Button>
            </div>
        </Fieldset>
    </div>
</template>
