<script setup>
import { ref, computed } from 'vue'
import { stringRegel, integerRegel } from './Rules.js'
import axios  from "axios";

// Definiere die Variablen für die Eingabefelder
const form = ref()
const formData = ref({
  regalbezeichnung: '',
  reihen: '',
  felder: '',
  ebenen: '',
  breite: '',
  hoehe: '',
  tiefe: ''
})


// Sendet die Daten an den Server
const sendeDaten = async () => {
  try {
    const response = await axios.post('http://localhost:8000/api/racks/post.php', { ...formData.value })
    console.log("Daten gesendet:", formData.value)
    console.log("Antwort vom Server",response.data)
    // Eingabedaten zurücksetzen
    form.value.reset()
    form.value.resetValidation()
  } catch (error) {
    console.error('Fehler beim Senden der Daten:', error)
  }
}

// Berechnet die Anzahl der Lagerplätze
const anzahlLagerplaetze = computed(() => {
  return formData.value.reihen * formData.value.tiefe * formData.value.hoehe

})

</script>

<template>
  <v-sheet class="mx-auto" width="1200" :elevation="30">
    <v-form ref="form" @submit.prevent="sendeDaten" class="align-center">
      <v-text-field
          v-model="formData.regalbezeichnung"
          :rules="stringRegel"
          label="Regalbezeichnung"
      ></v-text-field>

      <v-text-field
          v-model="formData.reihen"
          :rules="integerRegel"
          label="Reihe(n)"
      ></v-text-field>

      <v-text-field
          v-model="formData.felder"
          :rules="integerRegel"
          label="Feld(er) pro Reihe"
      ></v-text-field>

      <v-text-field
          v-model="formData.ebenen"
          :rules="integerRegel"
          label="Ebene(en) pro Feld"
      ></v-text-field>

      <!-- Flexbox-Container für Breite, Tiefe und Höhe -->
      <div class="dimension-row">
        <v-text-field
            v-model="formData.breite"
            :rules="integerRegel"
            label="Breite (cm)"
            class="fixed-size"
        ></v-text-field>

        <v-text-field
            v-model="formData.tiefe"
            :rules="integerRegel"
            label="Tiefe (cm)"
            class="fixed-size"
        ></v-text-field>

        <v-text-field
            v-model="formData.hoehe"
            :rules="integerRegel"
            label="Höhe (cm)"
            class="fixed-size"
        ></v-text-field>
      </div>

      <v-btn class="mt-2" type="submit" block>Lagerplatz erstellen</v-btn>
    </v-form>
    <div class="result">
      <p>Anzahl der Lagerplätze: {{ anzahlLagerplaetze }}</p>
    </div>
  </v-sheet>
</template>


<style scoped>
.fixed-size {
  width: 300px; /* Feste Breite für die Textfelder */
}
.dimension-row {
  display: flex;
  gap: 16px; /* Abstand zwischen den Feldern */
}
.result {
  margin-top: 20px;
  font-size: 18px;
  font-weight: bold;
  text-align: center;
}
</style>