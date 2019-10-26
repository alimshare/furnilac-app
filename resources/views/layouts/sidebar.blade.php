<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <!-- <div class="user-panel">
        <div class="pull-left image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>Alexander Pierce</p>
          <a href="/#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div> -->

      <!-- search form (Optional) -->
      <!-- <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
              <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
              </button>
            </span>
        </div>
      </form> -->
      <!-- /.search form -->

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
        <!-- Optionally, you can add icons to the links -->
        <li class="active"><a href="/"><i class="fa fa-home"></i> <span>Home</span></a></li>
        <li class="treeview">
          <a href="/#"><i class="fa fa-database"></i> <span>Master</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
          </a>
          <ul class="treeview-menu">
            <li><a href="/group"><i class="fa fa-users"></i>Group</a></li>
            <li><a href="/employee"><i class="fa fa-user"></i>Employee</a></li>
            <li><a href="/buyer"><i class="fa fa-user"></i>Buyer</a></li>
            <li><a href="/item"><i class="fa fa-cube"></i> Item & Parts</a></li>
          </ul>
        </li>
        <li><a href="/po"><i class="fas fa-clipboard-list"></i>&nbsp;&nbsp;<span> Purchase Order</span></a></li>
        <li><a href="/po/monitor"><i class="fa fa-tv"></i>&nbsp;&nbsp;<span> PO Monitor</span></a></li>

        <li class="header">Production</li>
        <li><a href="/po/production"><i class="fa fa-circle-o"></i>&nbsp;&nbsp;<span> Production</span></a></li>
        <li><a href="/po/mandays"><i class="fa fa-circle-o"></i>&nbsp;&nbsp;<span> Mandays</span></a></li>
        
        <li class="header">Export</li>
        <li><a href="/report/production"><i class="fa fa-circle-o"></i>&nbsp;&nbsp;<span> Production Report</span></a></li>
        <li><a href="/report/mandays"><i class="fa fa-circle-o"></i>&nbsp;&nbsp;<span> Mandays Report</span></a></li>
        <li><a href="/report/group"><i class="fa fa-circle-o"></i>&nbsp;&nbsp;<span> Group Salary Report</span></a></li>
        <li><a href="/report/group/summary"><i class="fa fa-circle-o"></i>&nbsp;&nbsp;<span> Summary Group Salary Report</span></a></li>
        <li><a href="/report/receh"><i class="fa fa-circle-o"></i>&nbsp;&nbsp;<span> Receh Report</span></a></li>
        <li><a href="/report/salary"><i class="fa fa-circle-o"></i>&nbsp;&nbsp;<span> Summary Salary Report</span></a></li>
        <li><a href="/report/tanda-terima"><i class="fa fa-circle-o"></i>&nbsp;&nbsp;<span> Tanda Terima Upah</span></a></li>
        
        <li class="header">Other</li>
        <li><a href="/user"><i class="fa fa-users"></i> <span>User Application</span></a></li>
        <li><a href="/change-password"><i class="fa fa-key"></i> <span> Change Password</span></a></li>
        <li>
          <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i>&nbsp;&nbsp;<span> Log Out</span>
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
          </form>
        </li>
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>