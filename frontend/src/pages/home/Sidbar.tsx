// import { useState } from 'react';
// import { Link, NavLink } from 'react-router-dom';
// import { 
//   FiHome, 
//   FiUsers, 
//   FiShoppingBag, 
//   FiTrendingUp, 
//   FiPackage, 
//   FiCreditCard, 
//   FiDollarSign, 
//   FiSettings, 
//   FiLogOut,
//   FiMenu,
//   FiX,
//   FiPieChart
// } from 'react-icons/fi';

// const Sidebar = () => {
//   const [isOpen, setIsOpen] = useState(false);

//   const menuItems = [
//     { path: '/', icon: <FiHome />, label: 'Tableau De Bord' },
//     { path: '/clients', icon: <FiUsers />, label: 'Clients', count: 143 },
//     { path: '/commandes', icon: <FiShoppingBag />, label: 'Commandes' },
//     { path: '/ventes', icon: <FiTrendingUp />, label: 'Ventes' },
//     { path: '/stocks', icon: <FiPackage />, label: 'Stocks', count: 143 },
//     { path: '/credits', icon: <FiCreditCard />, label: 'Credits', count: 143 },
//     { path: '/caisse', icon: <FiDollarSign />, label: 'Caisse' },
//     { path: '/parametre', icon: <FiSettings />, label: 'Paramètre' },
//   ];

//   return (
//     <>
//       {/* Mobile menu button */}
//       <button
//         onClick={() => setIsOpen(!isOpen)}
//         className="lg:hidden fixed top-4 left-4 z-50 p-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300"
//       >
//         {isOpen ? <FiX size={24} /> : <FiMenu size={24} />}
//       </button>

//       {/* Overlay for mobile */}
//       {isOpen && (
//         <div
//           className="lg:hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-40"
//           onClick={() => setIsOpen(false)}
//         />
//       )}

//       {/* Sidebar */}
//       <aside className={`
//         fixed top-0 left-0 z-40 h-screen 
//         bg-gradient-to-b from-gray-900 via-gray-800 to-gray-900
//         text-white transition-all duration-500 ease-in-out
//         ${isOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'}
//         w-72 lg:w-80 xl:w-72
//         shadow-2xl
//       `}>
//         {/* Logo Area avec effet de brillance */}
//         <div className="relative overflow-hidden p-6">
//           <div className="absolute inset-0 bg-gradient-to-r from-blue-500/20 to-purple-500/20 animate-pulse"></div>
//           <h1 className="relative text-2xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">
//             DMYArtDesign
//           </h1>
//           <p className="relative text-sm text-gray-400 mt-1">Espace Administration</p>
//         </div>

//         {/* Stats Cards */}
//         <div className="grid grid-cols-3 gap-3 px-4 mb-6">
//           <div className="bg-white/10 backdrop-blur-lg rounded-xl p-3 text-center transform hover:scale-105 transition-all duration-300 hover:bg-white/20">
//             <p className="text-xs text-gray-400">Clients</p>
//             <p className="text-xl font-bold text-blue-400">143</p>
//           </div>
//           <div className="bg-white/10 backdrop-blur-lg rounded-xl p-3 text-center transform hover:scale-105 transition-all duration-300 hover:bg-white/20">
//             <p className="text-xs text-gray-400">Stocks</p>
//             <p className="text-xl font-bold text-green-400">143</p>
//           </div>
//           <div className="bg-white/10 backdrop-blur-lg rounded-xl p-3 text-center transform hover:scale-105 transition-all duration-300 hover:bg-white/20">
//             <p className="text-xs text-gray-400">Credits</p>
//             <p className="text-xl font-bold text-purple-400">143</p>
//           </div>
//         </div>

//         {/* Navigation Menu */}
//         <nav className="px-4 space-y-1 overflow-y-auto max-h-[calc(100vh-250px)] scrollbar-thin scrollbar-thumb-gray-600">
//           {menuItems.map((item) => (
//             <NavLink
//               key={item.path}
//               to={item.path}
//               onClick={() => setIsOpen(false)}
//               className={({ isActive }) => `
//                 flex items-center justify-between px-4 py-3 rounded-xl
//                 transition-all duration-300 group
//                 ${isActive 
//                   ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg shadow-blue-600/25' 
//                   : 'text-gray-300 hover:bg-white/10 hover:text-white'
//                 }
//               `}
//             >
//               <div className="flex items-center space-x-3">
//                 <span className="text-xl group-hover:scale-110 transition-transform duration-300">
//                   {item.icon}
//                 </span>
//                 <span className="font-medium">{item.label}</span>
//               </div>
//               {item.count && (
//                 <span className={`
//                   px-2 py-1 text-xs font-bold rounded-full
//                   ${item.path === '/clients' ? 'bg-blue-500/20 text-blue-300' : 
//                     item.path === '/stocks' ? 'bg-green-500/20 text-green-300' :
//                     'bg-purple-500/20 text-purple-300'}
//                 `}>
//                   {item.count}
//                 </span>
//               )}
//             </NavLink>
//           ))}

//           {/* Déconnexion */}
//           <button className="w-full flex items-center space-x-3 px-4 py-3 mt-6 text-red-300 hover:bg-red-500/20 hover:text-red-300 rounded-xl transition-all duration-300 group">
//             <span className="text-xl group-hover:scale-110 transition-transform duration-300">
//               <FiLogOut />
//             </span>
//             <span className="font-medium">Déconnexion</span>
//           </button>
//         </nav>
//       </aside>
//     </>
//   );
// };

// export default Sidebar;
import { useState } from 'react';
import { NavLink } from 'react-router-dom';
import { 
  FiHome, 
  FiUsers, 
  FiShoppingBag, 
  FiTrendingUp, 
  FiPackage, 
  FiCreditCard, 
  FiDollarSign, 
  FiSettings, 
  FiLogOut,
  FiChevronDown,
  FiChevronRight,
  FiStar,
  FiAward,
  FiBarChart2
} from 'react-icons/fi';

const Sidebar = ({ isOpen, setIsOpen }) => {
  const [openSubmenus, setOpenSubmenus] = useState({});

  const toggleSubmenu = (label) => {
    setOpenSubmenus(prev => ({
      ...prev,
      [label]: !prev[label]
    }));
  };

  const menuItems = [
    { 
      path: '/', 
      icon: <FiHome />, 
      label: 'Tableau De Bord',
      badge: '9+',
      badgeColor: 'blue'
    },
    { 
      icon: <FiUsers />, 
      label: 'Clients', 
      count: 143,
      badge: 'Nouveau',
      badgeColor: 'green',
      submenu: [
        { path: '/clients/liste', label: 'Liste des clients', count: 143 },
        { path: '/clients/ajouter', label: 'Ajouter client', icon: '+' },
        { path: '/clients/groups', label: 'Groupes', count: 12 },
        { path: '/clients/archives', label: 'Archives', count: 23 }
      ]
    },
    { 
      path: '/commandes', 
      icon: <FiShoppingBag />, 
      label: 'Commandes',
      badge: '12',
      badgeColor: 'red',
      submenu: [
        { path: '/commandes/en-cours', label: 'En cours', count: 8 },
        { path: '/commandes/livrees', label: 'Livrées', count: 156 },
        { path: '/commandes/annulees', label: 'Annulées', count: 3 }
      ]
    },
    { 
      path: '/ventes', 
      icon: <FiTrendingUp />, 
      label: 'Ventes',
      badge: '+23%',
      badgeColor: 'purple'
    },
    { 
      icon: <FiPackage />, 
      label: 'Stocks', 
      count: 143,
      submenu: [
        { path: '/stocks/produits', label: 'Produits', count: 143 },
        { path: '/stocks/categories', label: 'Catégories', count: 8 },
        { path: '/stocks/alertes', label: 'Alertes', count: 3, badge: 'urgent' },
        { path: '/stocks/mouvements', label: 'Mouvements' }
      ]
    },
    { 
      icon: <FiCreditCard />, 
      label: 'Credits', 
      count: 143,
      submenu: [
        { path: '/credits/encours', label: 'En cours', count: 143 },
        { path: '/credits/rembourses', label: 'Remboursés', count: 89 },
        { path: '/credits/echus', label: 'Échus', count: 12, badge: 'alerte' }
      ]
    },
    { 
      path: '/caisse', 
      icon: <FiDollarSign />, 
      label: 'Caisse',
      badge: '1.2M FCFA',
      badgeColor: 'green'
    },
    { 
      icon: <FiSettings />, 
      label: 'Paramètre',
      submenu: [
        { path: '/parametre/profil', label: 'Mon profil' },
        { path: '/parametre/entreprise', label: 'Entreprise' },
        { path: '/parametre/utilisateurs', label: 'Utilisateurs', count: 5 },
        { path: '/parametre/securite', label: 'Sécurité' }
      ]
    },
  ];
  return (
    <>
      {/* Mobile overlay */}
      {isOpen && (
        <div
          className="lg:hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-40"
          onClick={() => setIsOpen(false)}
        />
      )}

      {/* Sidebar */}
      <aside className={`
        fixed top-0 left-0 z-50 h-screen 
        bg-gradient-to-b from-blue-900 via-blue-800 to-blue-900
        text-white transition-all duration-500 ease-out
        ${isOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'}
        w-80 shadow-2xl
        overflow-y-auto scrollbar-thin scrollbar-thumb-gray-600 scrollbar-track-gray-800
      `}>
        {/* ===== LOGO SECTION ===== */}
        <div className="sticky top-0 z-10 bg-gradient-to-b from-blue-900 to-blue-900 backdrop-blur-xl">
          <div className="relative p-6 border-b border-gray-700/50">
            {/* Effet de glow */}
            <div className="absolute inset-0 bg-gradient-to-r from-blue-500/20 to-purple-500/20 animate-pulse"></div>
            
            {/* Logo container */}
            <div className="relative flex items-center space-x-4">
              {/* Logo icon avec animation */}
              <div className="relative">
                <div className="">
                  {/* <span className="text-3xl font-black text-white">D</span> */}
                  <img src="IMG-20260128-WA0010.jpg" alt="" />
                </div>
              </div>
              
              {/* Logo text */}
              {/* <div className="flex-1">
                <h1 className="text-2xl font-bold bg-gradient-to-r from-blue-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">
                  DMYArtDesign
                </h1>
                <div className="flex items-center space-x-2 mt-1">
                  <div className="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                  <p className="text-xs text-gray-400">Espace Admin • N°143</p>
                </div>
              </div> */}
            </div>
            {/* Quick stats mini */}
          </div>
        </div>

        {/* ===== MENU PRINCIPAL ===== */}
        <nav className="p-4 space-y-1">
          {menuItems.map((item, index) => (
            <div key={index} className="relative">
              {/* Menu item with submenu */}
              {item.submenu ? (
                <div>
                  <button
                    onClick={() => toggleSubmenu(item.label)}
                    className="w-full flex items-center justify-between px-4 py-3 rounded-xl text-gray-300 hover:bg-white/10 hover:text-white transition-all duration-300 group"
                  >
                    <div className="flex items-center space-x-3">
                      <span className="text-xl group-hover:scale-110 group-hover:text-blue-400 transition-all duration-300">
                        {item.icon}
                      </span>
                      <span className="font-medium">{item.label}</span>
                      {item.count && (
                        <span className="px-2 py-0.5 text-xs bg-gray-700 rounded-full text-gray-300">
                          {item.count}
                        </span>
                      )}
                    </div>
                    <div className="flex items-center space-x-2">
                      {item.badge && (
                        <span className={`px-2 py-0.5 text-xs font-bold rounded-full bg-${item.badgeColor}-500/20 text-${item.badgeColor}-300`}>
                          {item.badge}
                        </span>
                      )}
                      <span className={`transition-transform duration-300 ${openSubmenus[item.label] ? 'rotate-180' : ''}`}>
                        <FiChevronDown />
                      </span>
                    </div>
                  </button>

                  {/* Submenu */}
                  <div className={`overflow-hidden transition-all duration-300 pl-12 ${openSubmenus[item.label] ? 'max-h-96 opacity-100 mt-1' : 'max-h-0 opacity-0'}`}>
                    {item.submenu.map((sub, subIdx) => (
                      <NavLink
                        key={subIdx}
                        to={sub.path || '#'}
                        className={({ isActive }) => `
                          flex items-center justify-between px-4 py-2.5 rounded-lg text-sm
                          transition-all duration-300 group relative
                          ${isActive 
                            ? 'bg-gradient-to-r from-blue-600/50 to-purple-600/50 text-white' 
                            : 'text-gray-400 hover:text-white hover:bg-white/5'
                          }
                        `}
                        onClick={() => setIsOpen(false)}
                      >
                        <div className="flex items-center space-x-3">
                          <span className="w-1 h-1 bg-gray-500 rounded-full group-hover:bg-blue-400 transition-colors"></span>
                          <span>{sub.label}</span>
                        </div>
                        {sub.count && (
                          <span className="text-xs bg-gray-700 px-2 py-0.5 rounded-full">
                            {sub.count}
                          </span>
                        )}
                        {sub.badge && (
                          <span className="text-xs bg-red-500/20 text-red-300 px-2 py-0.5 rounded-full animate-pulse">
                            {sub.badge}
                          </span>
                        )}
                      </NavLink>
                    ))}
                  </div>
                </div>
              ) : (
                /* Simple menu item (without submenu) */
                <NavLink
                  to={item.path}
                  className={({ isActive }) => `
                    flex items-center justify-between px-4 py-3 rounded-xl
                    transition-all duration-300 group relative overflow-hidden
                    ${isActive 
                      ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg shadow-blue-600/25' 
                      : 'text-gray-300 hover:bg-white/10 hover:text-white'
                    }
                  `}
                  onClick={() => setIsOpen(false)}
                >
                  {/* Effet de brillance au hover */}
                  <div className="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                  
                  <div className="flex items-center space-x-3 relative">
                    <span className="text-xl group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                      {item.icon}
                    </span>
                    <span className="font-medium">{item.label}</span>
                  </div>
                  
                  <div className="flex items-center space-x-2 relative">
                    {item.count && (
                      <span className="px-2 py-0.5 text-xs bg-white/20 rounded-full">
                        {item.count}
                      </span>
                    )}
                    {item.badge && (
                      <span className={`px-2 py-0.5 text-xs font-bold rounded-full bg-${item.badgeColor}-500/20 text-${item.badgeColor}-300`}>
                        {item.badge}
                      </span>
                    )}
                  </div>
                </NavLink>
              )}
            </div>
          ))}

          {/* Déconnexion button */}
          <div className="pt-4 mt-4 border-t border-gray-700/50">
            <button className="w-full flex items-center space-x-3 px-4 py-3 text-red-300/70 hover:bg-red-500/20 hover:text-red-300 rounded-xl transition-all duration-300 group relative overflow-hidden">
              <div className="absolute inset-0 bg-gradient-to-r from-red-500/0 via-red-500/10 to-red-500/0 -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
              <span className="text-xl group-hover:scale-110 group-hover:rotate-12 transition-all duration-300">
                <FiLogOut />
              </span>
              <span className="font-medium">Déconnexion</span>
              <span className="ml-auto text-xs opacity-50 group-hover:opacity-100 transition-opacity">
                ⌘Q
              </span>
            </button>
          </div>
        </nav>

        {/* Footer avec version */}
        <div className="sticky bottom-0 p-4 text-center border border-b-orange-50 text-xs text-white bg-blue-900 backdrop-blur">
          <p>DMYArtDesign • &copy; 2019</p>
        </div>
      </aside>
    </>
  );
};

export default Sidebar;