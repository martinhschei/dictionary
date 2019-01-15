<template>
	<vue-bootstrap-typeahead 
	  	:data="words"
	    v-model="wordSearch"
	    size="lg"
	    :serializer="s => s"
	    placeholder="Type..."
	    @hit="selectedWord = $event"
	/>
</template>

<script>

const API_URL = 'http://dictionary.wip?query=:query';

export default {
  name: 'TestComponent',

  data() {
    return {
   		query: '',
		words: [],
		wordSearch: '',
		selectedWord: null
    }
  },

  methods: {
    async getWords(query) {
      //const res = await fetch(API_URL.replace(':query', query))
      //const suggestions = await res.json()
      //this.words = suggestions.suggestions
      axios.get(`/api/lookup/${this.wordSearch}`).then((result) => {
      	this.words = result.data.items;
      });
    }
  },

  watch: {
    wordSearch: _.debounce(function(word) { this.getWords(word) }, 500)
  }
}
</script>
