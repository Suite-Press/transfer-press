<template>
  <div class="tranpr-header">
    <h2 class="title">
      <svg width="33" height="32" viewBox="0 0 33 32" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M7.80152 9.14362H11.3787C12.6375 9.14362 13.6544 10.1649 13.6544 11.4291C13.6544 12.6932 12.6375 13.7145 11.3787 13.7145H2.27574C1.01697 13.7145 0 12.6932 0 11.4291V2.28724C0 1.0231 1.01697 0.00178562 2.27574 0.00178562C3.53451 0.00178562 4.55148 1.0231 4.55148 2.28724V5.94398L5.80314 4.68698C12.0259 -1.56233 22.1102 -1.56233 28.333 4.68698C34.5557 10.9363 34.5557 21.0637 28.333 27.313C22.1102 33.5623 12.0259 33.5623 5.80314 27.313C4.91417 26.4203 4.91417 24.9704 5.80314 24.0777C6.6921 23.1849 8.13577 23.1849 9.02473 24.0777C13.4695 28.5415 20.6737 28.5415 25.1185 24.0777C29.5633 19.6139 29.5633 12.379 25.1185 7.91519C20.6737 3.4514 13.4695 3.4514 9.02473 7.91519L7.80152 9.14362Z" fill="#008080"/>
        <path d="M23.6179 10.1547C23.8948 10.3449 24.0411 10.6751 23.9906 11.0061L22.2895 22.3861C22.2496 22.6514 22.0893 22.8845 21.8547 23.0167C21.6202 23.1489 21.3387 23.1666 21.0895 23.0637L17.8141 21.719L15.9501 23.7527C15.7079 24.0189 15.3255 24.108 14.9887 23.9782C14.6519 23.8484 14.4318 23.524 14.4303 23.1632L14.4205 20.8777C14.4201 20.7684 14.4606 20.6643 14.5341 20.582L19.0946 15.5651C19.2524 15.3922 19.2458 15.1271 19.0811 14.9637C18.9164 14.8004 18.6512 14.7906 18.4796 14.9472L12.9401 19.9108L10.5211 18.7128C10.2307 18.5691 10.0435 18.2801 10.0339 17.9576C10.0244 17.635 10.1926 17.3336 10.4707 17.1711L22.6883 10.1204C22.9801 9.95241 23.341 9.96727 23.6179 10.1547Z" fill="#008080"/>
      </svg>
      TransferPress
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 42 42" fill="none">
          <path d="M14.6005 4.9C5.49678 3.80127 4.39805 10.7206 4.98665 14.3177C-2.23356 19.6543 1.97823 25.4358 4.98665 27.6595C4.20185 36.6063 11.0689 37.7965 14.6005 37.2733C20.0942 44.0227 25.6532 40.0856 27.746 37.2733C37.0067 37.7442 37.8831 31.0603 37.1637 27.6595C44.3839 22.0089 40.1721 16.4105 37.1637 14.3177C38.2624 5.37081 31.3431 4.31136 27.746 4.9C22.2524 -2.16327 16.6933 1.95697 14.6005 4.9Z" fill="#63B1FA" stroke="#63B1FA"/>
          <path d="M14.8329 19.1041C12.0075 18.1623 11.8244 20.4121 12.086 21.6547L17.3677 26.6257C18.1453 27.3576 19.3607 27.3495 20.1285 26.6073L30.529 16.5535C30.9999 13.7282 28.894 13.8067 27.7822 14.1991L18.7569 22.8319L14.8329 19.1041Z" fill="white" stroke="white"/>
      </svg>
      <span class="badge">→ Advanced Plugins Migrator Tools</span>
    </h2>

    <div class="menu">
      <a
          href="/wp-admin/admin.php?page=tranpr-export"
          class="menu-btn"
          :class="{ active: currentPage === 'tranpr-export' }"
      >Export</a>

      <a
          href="/wp-admin/admin.php?page=tranpr-import"
          class="menu-btn"
          :class="{ active: currentPage === 'tranpr-import' }"
      >Import</a>

      <a
          href="/wp-admin/admin.php?page=tranpr-support"
          class="menu-btn"
          :class="{ active: currentPage === 'tranpr-support' }"
      >Support</a>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      currentPage: ''
    };
  },
  mounted() {
    const urlParams = new URLSearchParams(window.location.search);
    this.currentPage = urlParams.get('page');
  }
};
</script>

<style scoped>
.tranpr-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0px 20px;
  background: white;
  position: sticky;
  top: 0;
  z-index: 9999;
  box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
  height: 90px;
  margin-bottom: 2.5rem;
}

.title {
  font-size: 1.2rem;
  color: #008080;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.badge {
  color: orange;
  background: blanchedalmond;
  padding: 0.3rem 0.8rem;
  border-radius: 1rem;
  font-size: 0.85rem;
  font-weight: 500;
}

.menu {
  display: flex;
  gap: 0.5rem;
  a{
    text-decoration: none;
  }
}

.menu-btn {
  background: #008080;
  color: white;
  border: none;
  border-radius: 0.2rem;
  padding: 0.4rem 0.9rem;
  font-size: 0.85rem;
  cursor: pointer;
  transition: background 0.2s ease;
}

.menu-btn:hover {
  background: #005f5f;
}

.menu-btn.active {
  background: #ffeccd;
  color: orange;
  font-weight: 500;
}
</style>
