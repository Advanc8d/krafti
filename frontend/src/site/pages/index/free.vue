<template>
  <b-modal ref="modal" :key="record.id" hide-footer visible size="xl" dialog-class="modal-video" @hidden="onHidden">
    <div class="wrapper">
      <b-embed
        :src="`https://player.vimeo.com/video/${record.video.remote_key}`"
        type="iframe"
        aspect="16by9"
        allowfullscreen
      />
      <div class="wrapper__content pt-3 pt-md-5">
        <div class="lesson__info container__940">
          <div class="container">
            <div class="d-flex flex-wrap align-items-center mb-3">
              <div class="lesson__info--description mb-2 mb-md-0">
                <div class="s-title mb-0">{{ record.title }}</div>
              </div>
              <b-row class="d-flex align-items-center col-12 col-md-auto ml-md-auto">
                <div v-b-tooltip="'Понравилось'">
                  <fa :icon="['fad', 'thumbs-up']" class="text-primary" />
                  {{ (record.likes_count - record.dislikes_count) | number }}
                </div>
                <div v-b-tooltip="'Просмотры'" class="ml-3">
                  <fa :icon="['fad', 'eye']" class="text-primary" />
                  {{ record.views_count | number }}
                </div>
              </b-row>
            </div>
            <div v-html="$md.render(record.description)" />
          </div>
        </div>
        <div v-if="record.products.length || record.file" class="what__is__needed container__940 mt-5">
          <div class="container">
            <div class="s-title">Что понадобится</div>
            <div class="needed__list">
              <div v-if="record.products.length" class="needed__list--wrapper d-flex justify-content-between flex-wrap">
                <div
                  v-for="product in record.products"
                  :key="product.id"
                  class="needed__item d-flex justify-content-between align-items-center"
                >
                  <div class="needed__item--title">{{ product }}</div>
                </div>
              </div>
              <div v-if="record.file" class="row">
                <div class="col-12">
                  <div class="download__materials">
                    <a class="button" :href="$file(record.file)" target="_self">Скачать материалы</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <section v-if="record.author" class="teacher__content container__940 mt-5">
          <div class="container">
            <div class="row">
              <div class="col-12">
                <div class="s-title">Преподаватель</div>
              </div>
            </div>
            <authors-list
              :authors="[record.author]"
              :show-desc="false"
              row-class="d-flex justify-content-center"
              item-class="col-9"
            />
          </div>
        </section>

        <section v-if="record.bonus" class="science__content container__940 mt-5">
          <div class="container">
            <div class="row">
              <div class="col-lg-8 offset-lg-2 col-12">
                <div class="wrapper__science">
                  <div class="science__content--img">
                    <img src="~assets/images/general/bg_science.png" alt="" />
                  </div>
                  <div class="science__content--pretitle">мини-лекция</div>
                  <h2 class="science__content--title">
                    {{ record.bonus.title }}
                  </h2>
                  <div class="science__content--text">
                    {{ record.bonus.description }}
                  </div>
                  <div class="science__content--video d-flex justify-content-center">
                    <div class="embed-responsive embed-responsive-16by9">
                      <iframe
                        v-if="record.bonus && record.bonus.remote_key"
                        id="bonus-vimeo-iframe"
                        class="embed-responsive-item"
                        :src="'https://player.vimeo.com/video/' + record.bonus.remote_key"
                        allowfullscreen
                        allow="autoplay; fullscreen"
                      ></iframe>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <section class="lesson__content--bottom container__940 mt-5">
          <div class="container">
            <client-only>
              <comments-list
                :course-id="record.course_id"
                :lesson-id="record.id"
                :url="urlComments"
                :read-only="true"
              />
            </client-only>
          </div>
        </section>

        <div class="alert alert-info mt-4 ml-4 mr-4 mb-0 d-flex align-items-center">
          Этот урок является лишь частью курса, который вы можете
          <nuxt-link :to="{name: 'courses-cid', params: {cid: record.course.id}}" class="btn btn-info ml-2"
            >приобрести прямо сейчас</nuxt-link
          >
        </div>
      </div>
    </div>
    <template slot="modal-header">
      <button class="close" type="button" aria-label="Close" @click="hideModal">
        <fa :icon="['fal', 'times']" size="2x" />
      </button>
    </template>
  </b-modal>
</template>

<script>
import AuthorsList from '../../components/authors-list'
import CommentsList from '../../components/comments/list'

export default {
  components: {CommentsList, AuthorsList},
  async asyncData({app, params, error}) {
    try {
      const id = params.id || -1
      const courseId = params.course_id || null
      const {data: record} = await app.$axios.get('web/free/lessons', {params: {id, course_id: courseId}})
      return {record}
    } catch (e) {
      error({statusCode: e.statusCode, message: e.data})
    }
  },
  data() {
    return {
      urlComments: 'web/free/comments',
      record: {},
    }
  },
  methods: {
    hideModal() {
      this.$refs.modal.hide()
    },
    onHidden() {
      this.$router.push({name: 'index'})
    },
  },
}
</script>

<style scoped lang="scss">
iframe {
  border-top-left-radius: 28.3099px;
  border-top-right-radius: 28.3099px;
}
</style>
