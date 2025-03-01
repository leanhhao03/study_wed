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
          <span class="trending-title">Subjects Type :</span>
            <label class="trending-wrapper" v-for="n in 4" :key="n">
              <input type="checkbox" class="trending-checkbox" v-model="selectedTrending" :value="'topic' + n">
                <span class="trending-item">topic{{ n }}</span>
            </label>
              </div>
                <div class="trending-row trending-bottom">
                  <label class="trending-wrapper" v-for="n in 4" :key="n + 4">
                    <input type="checkbox" class="trending-checkbox" v-model="selectedTrending" :value="'topic' + (n + 4)">
                      <span class="trending-item">topic{{ n + 4 }}</span>
            </label>
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