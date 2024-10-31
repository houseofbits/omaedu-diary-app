<script setup>
import { ref, watch } from "vue";

const props = defineProps({
  modelValue: Boolean,
});

const emit = defineEmits(["update:modelValue"]);

const localIsOpen = ref(props.modelValue);

watch(() => props.modelValue, () => {
    localIsOpen.value = props.modelValue;
});

watch(() => localIsOpen.value, ()=>{
    emit("update:modelValue", localIsOpen.value);
})

</script>

<template>
    <v-dialog
      v-model="localIsOpen"
      width="auto"
    >
      <v-card
        max-width="600"
        text="Informative message....."
        title="Welcome to your private diary"
      >
        <template v-slot:actions>
          <v-btn
            class="ms-auto"
            text="Ok"
            @click="localIsOpen = false"
          ></v-btn>
        </template>
      </v-card>
    </v-dialog>
</template>

