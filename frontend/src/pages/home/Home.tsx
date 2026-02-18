import { useState } from 'react';
import { 
  FiUsers, 
  FiPackage, 
  FiCreditCard, 
  FiShoppingBag,
  FiTrendingUp,
  FiDollarSign,
  FiPieChart
} from 'react-icons/fi';
import Sidebar from './Sidbar';
import Navbar from './Navbar';

const Home = () => {
  const [sidebarOpen, setSidebarOpen] = useState(false);

  const toggleSidebar = () => {
    setSidebarOpen(!sidebarOpen);
  };

  const stats = [
    { icon: <FiUsers />, label: 'Clients', value: '143', color: 'from-blue-500 to-blue-600', bg: 'bg-blue-50', text: 'text-blue-600' },
    { icon: <FiPackage />, label: 'Stocks', value: '143', color: 'from-green-500 to-green-600', bg: 'bg-green-50', text: 'text-green-600' },
    { icon: <FiCreditCard />, label: 'Credits', value: '143', color: 'from-purple-500 to-purple-600', bg: 'bg-purple-50', text: 'text-purple-600' },
    { icon: <FiShoppingBag />, label: 'Commandes', value: '89', color: 'from-yellow-500 to-yellow-600', bg: 'bg-yellow-50', text: 'text-yellow-600' },
  ];

  const vendors = [
    { name: 'Vendor 1', progress: 20, color: 'blue' },
    { name: 'Vendor 2', progress: 40, color: 'green' },
    { name: 'Vendor 3', progress: 60, color: 'purple' },
    { name: 'Vendor 4', progress: 80, color: 'orange' },
  ];

  return (
    <div className="min-h-screen bg-gray-50">
      <Sidebar isOpen={sidebarOpen} setIsOpen={setSidebarOpen} />
      
      <div className="lg:pl-72 xl:pl-72 transition-all duration-500">
        <Navbar toggleSidebar={toggleSidebar} />
        
        <main className="p-4 sm:p-6 lg:p-8">
          {/* Welcome Banner */}
          <div className="mb-8 bg-gradient-to-r from-blue-600 to-purple-600 rounded-3xl p-8 text-white relative overflow-hidden">
            <div className="absolute inset-0 bg-black/20"></div>
            <div className="relative z-10">
              <h1 className="text-3xl sm:text-4xl font-bold mb-2">
                Bienvenue chez DMYArtDesign! 🎨
              </h1>
              <p className="text-blue-100 text-lg">
                Client N°: 143 • Voici votre tableau de bord personnalisé
              </p>
            </div>
          </div>

          {/* Stats Grid */}
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {stats.map((stat, index) => (
              <div
                key={index}
                className="bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 border border-gray-100"
              >
                <div className={`${stat.bg} w-12 h-12 rounded-xl flex items-center justify-center mb-4`}>
                  <div className={`text-2xl ${stat.text}`}>{stat.icon}</div>
                </div>
                <h3 className="text-gray-500 text-sm mb-1">{stat.label}</h3>
                <p className="text-3xl font-bold text-gray-800">{stat.value}</p>
              </div>
            ))}
          </div>

          {/* Vendors Section */}
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            {/* Vendors Progress */}
            <div className="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
              <div className="flex items-center justify-between mb-6">
                <h2 className="text-xl font-bold text-gray-800">Vendors N°: 143</h2>
                <button className="text-sm bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-xl hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                  + Ajouter
                </button>
              </div>
              
              <div className="space-y-4">
                {vendors.map((vendor, index) => (
                  <div key={index} className="bg-gray-50 rounded-xl p-4">
                    <div className="flex justify-between mb-2">
                      <span className="font-medium text-gray-700">{vendor.name}</span>
                      <span className="text-gray-600">{vendor.progress}%</span>
                    </div>
                    <div className="w-full bg-gray-200 rounded-full h-3">
                      <div
                        className={`bg-gradient-to-r from-${vendor.color}-500 to-${vendor.color}-600 h-3 rounded-full transition-all duration-500`}
                        style={{ width: `${vendor.progress}%` }}
                      ></div>
                    </div>
                  </div>
                ))}
              </div>
            </div>

            {/* Recent Clients */}
            <div className="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
              <div className="flex items-center justify-between mb-6">
                <h2 className="text-xl font-bold text-gray-800">Clients Récents</h2>
                <button className="text-sm bg-gradient-to-r from-green-600 to-teal-600 text-white px-4 py-2 rounded-xl hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                  Voir tout
                </button>
              </div>
              
              <div className="space-y-4">
                {[1, 2, 3, 4, 5].map((i) => (
                  <div key={i} className="flex items-center justify-between p-3 hover:bg-gray-50 rounded-xl transition-colors">
                    <div className="flex items-center space-x-3">
                      <div className="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold">
                        C{i}
                      </div>
                      <div>
                        <p className="font-medium text-gray-800">Client {i}</p>
                        <p className="text-sm text-gray-500">client{i}@email.com</p>
                      </div>
                    </div>
                    <span className="text-xs font-semibold px-2 py-1 bg-green-100 text-green-600 rounded-full">
                      Actif
                    </span>
                  </div>
                ))}
              </div>
            </div>
          </div>

          {/* Bottom Stats */}
          <div className="grid grid-cols-2 sm:grid-cols-4 gap-4">
            {['20%', '40%', '60%', '80%'].map((percent, i) => (
              <div key={i} className="bg-white rounded-xl p-4 text-center shadow hover:shadow-lg transition-all duration-300">
                <div className="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                  {percent}
                </div>
                <div className="text-sm text-gray-500 mt-1">Croissance</div>
              </div>
            ))}
          </div>
        </main>
      </div>
    </div>
  );
};

export default Home;