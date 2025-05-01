<script setup>
import { ref } from 'vue'
import Form from './components/Form.vue'
import Table from './components/Table.vue'

const showTable = ref(false) // Zustand, ob die Tabelle angezeigt wird

const handleFormSubmit = () => {
  showTable.value = true // Tabelle anzeigen, wenn das Formular abgeschickt wird
}
</script>

<template>
  <div>
    <h1 class="text-center">Regal-Lagersystem</h1>
    <Form class="mt-5" @form-submitted="handleFormSubmit" />
    <div v-if="showTable" class="mt-5">
      <Table />
    </div>
  </div>
</template>