import { useState } from 'react';
import { FiSearch, FiBell, FiUser, FiChevronDown, FiMenu } from 'react-icons/fi';

const Navbar = ({ toggleSidebar }) => {
  const [showProfile, setShowProfile] = useState(false);
  const [showNotifications, setShowNotifications] = useState(false);

  return (
    <nav className="bg-white/80 backdrop-blur-xl shadow-lg sticky top-0 z-30 border-b border-gray-200/50">
      <div className="px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-20">
          {/* Left section - Mobile menu and page title */}
          <div className="flex items-center space-x-4">
            <button
              onClick={toggleSidebar}
              className="lg:hidden p-2 hover:bg-gray-100 rounded-xl transition-all duration-300"
            >
              <FiMenu size={24} className="text-gray-600" />
            </button>
            <h2 className="text-xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
              Tableau de Bord
            </h2>
          </div>

          {/* Search bar - Hidden on mobile */}
          <div className="hidden md:flex items-center flex-1 max-w-md mx-8">
            <div className="relative w-full">
              <FiSearch className="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400" />
              <input
                type="text"
                placeholder="Rechercher..."
                className="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all duration-300"
              />
            </div>
          </div>

          {/* Right section - Notifications and Profile */}
          <div className="flex items-center space-x-3 sm:space-x-4">
            {/* Notifications */}
            <div className="relative">
              <button
                onClick={() => setShowNotifications(!showNotifications)}
                className="relative p-3 hover:bg-gray-100 rounded-xl transition-all duration-300 group"
              >
                <FiBell size={22} className="text-gray-600 group-hover:text-blue-600 transition-colors" />
                <span className="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full animate-ping"></span>
                <span className="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full"></span>
              </button>

              {/* Notifications dropdown */}
              {showNotifications && (
                <div className="absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden transform transition-all duration-300">
                  <div className="p-4 bg-gradient-to-r from-blue-600 to-purple-600">
                    <h3 className="text-white font-semibold">Notifications</h3>
                  </div>
                  <div className="divide-y divide-gray-100">
                    {[1, 2, 3].map((i) => (
                      <div key={i} className="p-4 hover:bg-gray-50 transition-colors cursor-pointer">
                        <p className="text-sm font-medium text-gray-800">Nouvelle commande #{i}</p>
                        <p className="text-xs text-gray-500 mt-1">Il y a 5 minutes</p>
                      </div>
                    ))}
                  </div>
                </div>
              )}
            </div>

            {/* Profile */}
            <div className="relative">
              <button
                onClick={() => setShowProfile(!showProfile)}
                className="flex items-center space-x-3 p-2 hover:bg-gray-100 rounded-2xl transition-all duration-300 group"
              >
                <div className="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold shadow-lg">
                  JD
                </div>
                <div className="hidden sm:block text-left">
                  <p className="text-sm font-semibold text-gray-800">Jean Dupont</p>
                  <p className="text-xs text-gray-500">Administrateur</p>
                </div>
                <FiChevronDown className="text-gray-500 group-hover:text-blue-600 transition-colors" />
              </button>

              {/* Profile dropdown */}
              {showProfile && (
                <div className="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden transform transition-all duration-300">
                  <div className="p-3 border-b border-gray-100">
                    <p className="text-sm font-semibold text-gray-800">Jean Dupont</p>
                    <p className="text-xs text-gray-500">jean@example.com</p>
                  </div>
                  <div className="p-2">
                    <a href="/profil" className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-xl transition-colors">
                      Mon Profil
                    </a>
                    <a href="/settings" className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-xl transition-colors">
                      Paramètres
                    </a>
                    <hr className="my-2 border-gray-100" />
                    <button className="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                      Déconnexion
                    </button>
                  </div>
                </div>
              )}
            </div>
          </div>
        </div>
      </div>
    </nav>
  );
};

export default Navbar;