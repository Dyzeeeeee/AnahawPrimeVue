<script setup>
import { onMounted, reactive, ref, watch } from 'vue';

</script>

<template>

    <div class="flex flex-row gap-2 relative">

        <div class="flex-grow-0 ">
            <div class="mb-3 gap-2 flex">
                <Button icon="pi pi-caret-left" class="p-button-secondary" label="" @click="redirectToPayment" />
                <div class="p-d-flex p-jc-center p-ai-center" style="flex-grow: 1;"></div> <!-- Spacer -->
                <Button icon="pi pi-user-plus" class="p-button-info" label="Customer" />
            </div>
            <Fieldset class="flex-grow flex-row" unstyled style="height: 80vh;">
                <template #legend>
                    <div class="flex align-items-center pl-2">
                        <i class="pi pi-book mr-2 text-xl"> </i>
                        <span class="font-bold text">Receipt</span>
                    </div>
                </template>
            </Fieldset>
        </div>
        <Fieldset class="flex-auto" unstyled style="height: 80vh;">
            <template #legend>
                <div class="flex align-items-center pl-2">
                    <i class="pi pi-cart-plus mr-2 text-xl"></i>
                    <span class="font-bold ">Products</span>
                </div>
            </template>
        </Fieldset>
    </div>
</template>
