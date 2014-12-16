<div class="wrap">

    <h2>Custom Sidebars</h2>

    <br class="clear"/>

    <div id="col-container">

        <div id="col-right">
            <div class="col-wrap form-wrap">
                <h3>Existing Sidebars</h3>

                <table class="wp-list-table widefat fixed" cellspacing="0" id="custom-sidebars">
                    <thead>
                    <tr>
                        <th class="manage-column column-name">Name</th>
                        <th class="manage-column column-slug">Slug</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th class="manage-column column-name">Name</th>
                        <th class="manage-column column-slug">Slug</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    <?php foreach ($this->sidebars as $id => $name) : ?>
                        <?php echo $this->sidebar_output($id, $name); ?>
                    <?php endforeach; ?>
                    </tbody>

                </table>

            </div>
            <!-- .col-wrap.form-wrap -->
        </div>
        <!-- #col-right -->

        <div id="col-left">
            <div class="col-wrap form-wrap">
                <h3>Add New Sidebar</h3>

                <form id="sneek-sidebar" name="sneek-sidebar" method="post" action="" class="validate">

                    <input name="action" value="add-sidebar" type="hidden"/>
                    <?php wp_nonce_field('sneek_add_sidebar', Sneek_Custom_Sidebars::NONCE); ?>

                    <div class="form-field form-required">
                        <label for="sidebar-name">Name</label>
                        <input name="sidebar-name" id="sidebar-name" value="" size="40" aria-required="true" type="text"/>
                    </div>

                    <p class="submit">
                        <button type="submit" id="submit" class="button button-primary">Add New Sidebar</button>
                    </p>

                </form>

            </div>
            <!-- .col-wrap.form-wrap -->
        </div>
        <!-- #col-left -->

    </div>
    <!-- #col-container -->
</div><!-- .wrap -->