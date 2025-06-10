<template>
  <div class="plugins-transfer-press" style="margin-left: -20px!important;">
    <TitleVue />

    <div style="padding: 0 2rem;">

      <div class="tranpr-support-container">
        <div class="tranpr-support-wrapper">

          <div class="tranpr-support-card">
            <h3>Getting Started with the TransferPress WordPress Plugin</h3>
            <p>Getting started with TransferPress, you can easily download and install plugins without the need for FTP access or a File Manager or other plugins. </p>
            <iframe width="560" height="315" src="https://www.youtube.com/embed/PkrE79dy3Ac?si=iAOFVNLJS_Kz2c77" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
          </div>
          <div class="tranpr-support-card">

            <h3>TransferPress Guide</h3>
            <p>Here are some helpful articles to help you get started with TransferPress.</p>
            <ul>
              <li><span class="dashicons dashicons-arrow-right-alt2"></span><a href="https://suitepress.org/docs/install-activate-and-update-transferpress/" target="_blank"> Install, Activate, and Update the TransferPress</a></li>
              <li><span class="dashicons dashicons-arrow-right-alt2"></span><a href="https://suitepress.org/docs/how-to-export-any-plugin-from-wordpress-using-transferpress/" target="_blank"> Export Any Plugin from WordPress Using TransferPress </a></li>
              <li><span class="dashicons dashicons-arrow-right-alt2"></span><a href="https://suitepress.org/docs/how-to-import-and-activate-any-plugin-with-one-click-using-transferpress/" target="_blank">  One Click Import and Activate Any Plugin Using TransferPress</a></li>
              <li><span class="dashicons dashicons-arrow-right-alt2"></span> <a href="#"> Export a plugin file + DB <span style="color: red; font-weight: bolder">(Upcoming Feature)</span> </a></li>
              <li><span class="dashicons dashicons-arrow-right-alt2"></span><a href="#"> Import a plugin file + DB <span style="color: red;  font-weight: bolder">(Upcoming Feature)</span></a></li>
            </ul>

            <div class="tranpr-information-tabs">

              <a class="tranpr-information-card" href="https://suitepress.org/support-ticket/" target="_blank">
                <span class="dashicons dashicons-superhero"></span>
              <p>  Need Technical Support?</p>
              </a>
              <a class="tranpr-information-card" href="https://wordpress.org/plugins/transfer-press/#reviews" target="_blank">
                <span class="dashicons dashicons-heart"></span>
              <p>  Leave a review</p>
              </a>

            </div>

          </div>

        </div>
      </div>

    </div>

  </div>
</template>

<script setup>
import TitleVue from "./Components/TitleVue.vue";

</script>


<style scoped>
.tranpr-support-container{

    .tranpr-support-wrapper{
      display: grid;
      grid-template-columns: repeat(2,1fr);
      grid-gap: 1rem;

      .tranpr-support-card{
        background: #ffffff;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: rgb(2 128 128 / 15%) 3px 3px 6px 0px inset, rgba(255, 255, 255, 0.5) -3px -3px 6px 1px inset;
        transition: transform 0.2s ease;

        p{
          font-size: 16px;
        }
        ul{
          li{
            font-size: 16px;
            :hover, span{
                color: #008080;
            }
            a{
              text-decoration: none;
              color: #0b0b0b;
            }
          }
        }

        .tranpr-information-tabs{
          margin-top: 2rem;
          display: grid;
          grid-template-columns: repeat(2,1fr);
          grid-gap: 1rem;
          text-align: center;

          .tranpr-information-card{
            background: #ffffff;
            padding: 1.5rem .5rem;
            border-radius: 12px;
            transition: transform 0.2s ease;
            box-shadow: rgb(2 128 128 / 15%) 3px 3px 6px 0px inset, rgba(255, 255, 255, 0.5) -3px -3px 6px 1px inset;
            display: flex;
            align-content: center;
            justify-content: center;
            flex-direction: column;
            text-decoration: none;
            font-size: 18px;
            position: relative;
            color: #008080;

            :hover{
              color: #008080;
            }
            span{
              font-size: 35px;
              position: absolute;
              left: 40%;
              top: 10%;
              color: #008080;
            }
          }
        }
      }
    }
}

@media (max-width: 768px) {
  .tranpr-support-wrapper{
    grid-template-columns: repeat(1,1fr);
  }
}

</style>
