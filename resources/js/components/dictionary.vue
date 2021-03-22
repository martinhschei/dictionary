<template>
  <div class="dictionary">
    <div class="row mb-2">
      <div class="col-3"></div>
      <div class="col-6">
        <vue-bootstrap-typeahead
          size="lg"
          :serializer="(s) => s"
          :data="words"
          v-model="wordSearch"
          placeholder="Type..."
        />
      </div>
    </div>
    <div class="row">
      <div class="col-3"></div>
      <div class="col-6 text-right">
        <button @click.prevent="send" class="btn btn-success">Send</button>
      </div>
      <div class="col-2"></div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      query: "",
      words: [],
      wordSearch: "",
      selectedWord: null,
    };
  },

  methods: {
    send() {
      axios.post("/api/dictionary/send", {
        sentence: this.wordSearch,
      });
    },

    async getWords(query) {
      axios.get(`/api/dictionary/lookup/${this.wordSearch}`).then((result) => {
        this.words = result.data;
      });
    },
  },

  watch: {
    wordSearch: _.debounce(function (word) {
      if (word.length > 0) {
        this.getWords(word);
      }
    }, 500),
  },
};
</script>
