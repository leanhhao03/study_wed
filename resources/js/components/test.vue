<template>
    <div id="test">
      <div class="header">
        <div class="logo">logo</div>
        <div class="title">Thinksync</div>
      </div> 
      <div class="test-container">
        <div class="search-container">
          <div class="search-bar">
            <input type="text" v-model="searchQuery" />
            <FontAwesomeIcon :icon="['fas', 'magnifying-glass']" class="search-icon" />
            <button class="btn-search">Tìm kiếm</button>
          </div>
  
          <div class="search-trending">
            <div class="trending-row">
              <span class="trending-title">Trending Now:</span>
              <div class="trending-item" v-for="n in 4" :key="n">
                topic{{ n }}
              </div>
            </div>
            <div class="trending-row trending-bottom">
              <div class="trending-item" v-for="n in 4" :key="n + 4">
                topic{{ n + 4 }}
              </div>
            </div>
          </div>
        </div>
  
        <div class="test-list">
          <div class="test-card" v-for="n in 8" :key="n">
            <a :href="'/'" class="test-card-link">
              <div class="test-title">Name of test {{ n }}</div>
              <div class="test-info">
                <span>Time for test: {{ 50 }} min/</span>
                <span>Member joined: {{  }}</span>
              </div>
              <div class="test-questions">Number of questions: {{ 40 }}</div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </template> 
  
  <script setup>
  import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
  import { library } from '@fortawesome/fontawesome-svg-core';
  import {faMagnifyingGlass} from '@fortawesome/free-solid-svg-icons';
  
  library.add(faMagnifyingGlass);
  </script>